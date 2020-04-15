<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\Notification;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\NotificationRepository;

class NotificationEloquentRepository extends AbstractEloquentRepository implements NotificationRepository
{
    public function model()
    {
        return new Notification;
    }

    public function getData($with = [], $data = [], $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->where($data)
            ->orderBy('viewed', 'asc')
            ->orderBy('created_at', 'desc');
    }

    public function store($data)
    {
        return $this->model()->create($data);
    }

    public function paginate($items, $perPage = null, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]);
    }

    public function getNotifications($with = [], $data = [], $dataSelect = ['*'])
    {
        $allRecords = [];
        $records = $this->getData($with, $data)->get();
        foreach ($records as $record) {
            switch ($record->target_type) {
                case config('model.target_type.book_user'):
                    if ($record->target) {
                        $book = $record->target->book;
                        if ($book) {
                            if($record->target->type == config('view.request.waiting') && bookBorrowed($record->target->owner_id, $record->target->book_id) && $record->receive_id != \Auth::id()){
                                $message = trans('settings.notifications.noAvailable', ['book' => $book->title]);
                                $record = array_add($record, 'route', config('view.notifications.route.book'));
                                $record = array_add($record, 'link', $book->slug . '-' . $book->id);
                            }else{
                                $message = translate(config('view.notifications.' . $record->target->type), $record->receive_id !== $record->target->owner_id ? true : false) . $book->title;
                                $type = $record->target->type;
                                if ($type == config('view.request.waiting') ||
                                    $type == config('view.request.returning') || $type == config('view.request.abtExpire')) {
                                    $record = array_add($record, 'route', config('view.notifications.route.owner_prompt'));
                                    $record = array_add($record, 'link', null);
                                } else {
                                    $record = array_add($record, 'route', config('view.notifications.route.book'));
                                    $record = array_add($record, 'link', $book->slug . '-' . $book->id);
                                }
                            }
                            $record = array_add($record, 'message', $message);
                            array_push($allRecords, $record);
                        }
                    }
                    break;

                case config('model.target_type.review'):
                    $book = $record->target->book;
                    if ($book) {
                        $record = array_add($record, 'message', __('settings.notifications.review') . $book->title);
                        $record = array_add($record, 'route', config('view.notifications.route.review'));
                        $link = [
                            $book->slug . '-' . $book->id,
                            $record->target->id,
                        ];
                        $record = array_add($record, 'link', $link);
                        array_push($allRecords, $record);
                    }
                    break;

                case config('model.target_type.follow'):
                    $record = array_add($record, 'message', __('settings.notifications.follow'));
                    $record = array_add($record, 'route', config('view.notifications.route.user'));
                    $record = array_add($record, 'link', $record->send_id);
                    array_push($allRecords, $record);
                    break;

                case config('model.target_type.vote'):
                    $book = $record->target->review->book;
                    if ($record->target->status == 1) {
                        $record = array_add(
                            $record,
                            'message',
                            __('settings.notifications.upvote') . $book->title
                        );
                    } elseif ($record->target->status == -1) {
                        $record = array_add(
                            $record,
                            'message',
                            __('settings.notifications.downvote') . $book->title
                        );
                    }
                    $record = array_add($record, 'route', config('view.notifications.route.review'));
                    $link = [
                        $book->slug . '-' . $book->id,
                        $record->target->review->id,
                    ];
                    $record = array_add($record, 'link', $link);
                    array_push($allRecords, $record);
                    break;

                case config('model.target_type.user'):
                    $record = array_add($record, 'message', __('settings.notifications.prompt'));
                    $record = array_add($record, 'route', config('view.notifications.route.owner_prompt'));
                    $record = array_add($record, 'link', null);
                    array_push($allRecords, $record);
                    break;

                case config('model.target_type.book'):
                    if ($record->target) {
                        $book = $record->target;
                        $record = array_add(
                            $record,
                            'message',
                            __('settings.notifications.create_book') . $book->title
                        );
                        $record = array_add($record, 'route', config('view.notifications.route.book'));
                        $record = array_add($record, 'link', $book->slug . '-' . $book->id);
                    }
                    array_push($allRecords, $record);
                    break;
                default:
                    break;
            }
        }

        return collect($allRecords);
    }

    public function find($data)
    {
        return $record = $this->getData([], $data)->first();
    }

    public function update($data)
    {
        try {
            $record = $this->model()->findOrFail($data['id']);

            return $record->update($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($data)
    {
        try {
            $record = $this->model()->where($data);

            return $record->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function markRead($data)
    {
        $view['viewed'] = 1;
        foreach ($data as $notification) {
            $notification->update($view);
        }

        return $data;
    }

    public function sendNotificationToFollowers($book_id, $follower_ids, $user_id)
    {
        $datas = [];
        foreach ($follower_ids as $follower_id){
            $data = [
                'send_id' => $user_id,
                'receive_id' => $follower_id,
                'target_type' => config('model.target_type.book'),
                'target_id' => $book_id,
                'viewed' => config('model.viewed.false'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            array_push($datas, $data);
        }
        try {
            $this->model()->insert($datas);

            return true;
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
