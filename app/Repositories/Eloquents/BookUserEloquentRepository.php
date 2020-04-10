<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\BookUser;
use App\Eloquent\Notification;
use App\Repositories\Contracts\BookUserRepository;
use App\Repositories\Contracts\NotificationRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;

class BookUserEloquentRepository extends AbstractEloquentRepository implements BookUserRepository
{
    public function model()
    {
        return new BookUser;
    }

    public function __construct(
        NotificationRepository $notification
    ) {
        $this->notification = $notification;
    }

    public function getData($data = [], $with = [], $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->where($data)
            ->get();
    }

    public function updateBookUser($type, $id, $date_return = false)
    {
        $query = "UPDATE book_user SET type = '$type'" .
            ( $date_return ? " , date_return = '$date_return'" : '')
            . " WHERE id = $id";
        DB::select($query);
    }

    public function store($data)
    {
        return $this->model()->create($data);
    }

    public function findWaitingList($id)
    {
        return $this->getData([
            'book_id' => $id,
            'type' => config('model.book_user.type.waiting'),
            'approved' => 0,
        ]);
    }

    public function destroy($data)
    {
        try {
            $records = $this->model()->where($data)->get();
            if ($records) {
                foreach ($records as $record) {
                    $record->delete();
                }

                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateBookRequest($data)
    {
        try {
            $bookRequest = $this->model()->findOrFail($data['id']);
            if (isset($data['status'])) {
                switch ($data['status']) {
                    case config('view.request.waiting'):
                        $type['type'] = 'reading';
                        $bookRequest->update($type);
                        $this->sendNofiticationToUserWaiting($bookRequest);
                        break;
                    case config('view.request.reading'):
                        $type = 'returning';
                        $this->updateBookUser($type, $bookRequest->id);
                        break;
                    case config('view.request.returning'):
                        $type = 'returned';
                        $date_return = date("Y-m-d H:i:s");
                        $this->updateBookUser($type, $bookRequest->id, $date_return);
                        break;
                    case config('view.request.dismiss'):
                        $type['type'] = 'cancel';
                        $bookRequest->update($type);
                        break;
                    default:
                        # code...
                        break;
                }

                return $bookRequest;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDataRequest($data = [], $with = [], $types = [], $dataSelect = ['*'], $attribute = ['id', 'desc'])
    {
        return $this->model()
            ->booksInTypes($types)
            ->select($dataSelect)
            ->with($with)
            ->where($data)
            ->whereNotIn('type', ['hasExtended', 'cancelExtend'])
            ->whereHas('book', function ($query) {
                $query->where('deleted_at', null);
            })
            ->orderBy($attribute[0], $attribute[1])
            ->paginate(config('view.paginate.book_request'), ['*'], isset($data['type']) ? $data['type'] : 'page');
    }

    public function getDetailData($request, $with = ['user'], $attribute = ['id', 'desc'])
    {
        try {
            return $this->model()
                ->join('users', 'users.id', '=', 'book_user.user_id')
                ->select(['users.name', 'users.id'])
                ->distinct('user_id')
                ->with($with)
                ->where($request)
                ->orderBy($attribute[0], $attribute[1])
                ->paginate(config('view.paginate.book_request'), ['*'], isset($request['type']) ? $request['type'] : 'page');

        } catch (Exception $e) {
            return null;
        }
    }

    public function getBorrowingData($data = [], $with = [], $dataSelect = ['*'])
    {
        return $this->getData($data)->groupBy('owner_id');
    }

    public function getPromptList()
    {
        $date = date_modify(Carbon::now(), config('view.prompt.time'))->format('Y-m-d H:i:s');
        $result = DB::table('book_user')
            ->select(['*'])
            ->where('type', config('model.book_user.type.reading'))
            ->whereRaw("DATEDIFF('$date', `updated_at`) >= `days_to_read`")
            ->get()
            ->groupBy('owner_id');

        return $result;
    }

    /**
     * returnBook
     * function update type book is returning
     * @param  mixed $id
     * id is id of book
     * @return void
     */

    public function returnBook($id)
    {
        $bookUser = $this->model()->where('book_id', $id)
                    ->where('user_id', Auth::id())
                    ->whereNotIn('type', $this->rejectStatuses())
                    ->orderBy('created_at', 'desc')
                    ->first();
        $type = config('view.request.returning');

        $data = [
            'type' => config('view.request.abtExpire'),
            'owner_id' => $bookUser->owner_id,
            'book_id' => $bookUser->book_id,
            'user_id' => $bookUser->user_id,
        ];

        $expire = $this->model()->where($data)->first();
        if($expire){
            $this->notification->destroy(['target_id' => $expire->id]);
            $expire->delete();
        }

        $this->notification->destroy([
            'send_id' => $bookUser->owner_id,
            'receive_id' => $bookUser->user_id,
            'target_type' => config('model.target_type.book_user'),
            'target_id' => $bookUser->id,
        ]);
        $this->notification->store([
            'send_id' => Auth::id(),
            'receive_id' => $bookUser->owner_id,
            'target_type' => config('model.target_type.book_user'),
            'target_id' => $bookUser->id,
            'viewed' => config('model.viewed.false'),
        ]);

        return $this->updateBookUser($type, $bookUser->id);
    }

    /**
     * getTypeBook
     * get type of book is reading for show user reading book
     * @param  mixed $idBook
     * id book
     * @return void
     */
    public function getTypeBook($idBook)
    {
        $bookTypes = $this->model()
                    ->select('days_to_read', 'user_id', 'owner_id', 'created_at', 'updated_at')
                    ->where('book_id', $idBook)
                    ->where('type', config('view.request.reading'))
                    ->get();

        if (isset($bookTypes)) {
            $data = [];
            foreach ($bookTypes as $key => $bookType) {
                $data[$key]['dateReturn'] = null;
                if (!is_null($bookType->created_at)) {
                    $data[$key]['dateReturn'] = date('d/m/y', strtotime($bookType->updated_at)
                                                + $bookType->days_to_read * 86400);
                }
                $data[$key]['userBorrow'] = $bookType->user->name;
                $data[$key]['owner'] = $bookType->owner->name;
            }

            return $data;
        }

        return 0;
    }

    /**
     * getBookStatusForUser
     * get status book for user login
     * @param  mixed $idBook
     * id book
     * @return void
     */
    public function getBookStatusForUser($idBook)
    {

        return $this->model()->where('book_id', $idBook)
                    ->where('user_id', Auth::id())
                    ->whereNotIn('type', $this->rejectStatuses())
                    ->orderByDesc('created_at')
                    ->first();
    }

    public function findId($id)
    {
        return $this->model()->where('id', $id)->first();
    }

    public function countReturned($id)
    {
        $result = $this->model()
            ->select(DB::raw('COUNT(user_id) as book_returned'))
            ->where('book_id', $id)
            ->where('type', 'returned')
            ->groupBy('book_id')
            ->first();

        return $result;
    }

    public function findByBookIdAndUserId($bookId, $userId)
    {
        return $this->model()->where('book_id', $bookId)->where('user_id', $userId)->first();
    }

    public function anyReturningOrReading($book_id, $owner_id) :bool
    {
        return $this->model()
            ->where([
                'book_id'=> $book_id,
                'owner_id' => $owner_id
            ])
            ->WhereIn('type', ['reading', 'returning'])
            ->count() > 0;
    }

    public function updateExpire($id, $status = false)
    {
        $bookUser = $this->model()->findOrFail($id);
        return $this->saveWithoutTimestamps($bookUser, $status);
    }

    public function saveWithoutTimestamps($object, $status, $days = 0)
    {
        $object->expire = $status;
        $object->days_to_read = $object->days_to_read + $days;
        $object->timestamps = false;
        $object->save();
        return $object->fresh();
    }

    public function handleExpire($req)
    {
        DB::beginTransaction();
        try {
            $currentBookBorrow = $this->findId($req['id']);
            $data = [
                'type' => 'reading',
                'owner_id' => $currentBookBorrow->owner_id,
                'book_id' => $currentBookBorrow->book_id,
                'user_id' => $currentBookBorrow->user_id,
                'expire' => false
            ];
            $bookUser = $this->model()->where($data)->first();
            if($req['type'] === __('settings.request.approve')){
                $status = true;
                $type = config('view.request.hasExtended');
                $days = $currentBookBorrow->days_to_read;
            }else{
                $status = false;
                $type = config('view.request.cancelExtend');
                $days = 0;
            }
            $this->saveWithoutTimestamps($bookUser, $status, $days);
            $currentBookBorrow->update(['type' => $type]);
            $this->notification->store([
                'send_id' => $currentBookBorrow->owner_id,
                'receive_id' => $currentBookBorrow->user_id,
                'target_type' => config('model.target_type.book_user'),
                'target_id' => $currentBookBorrow->id,
                'viewed' => config('model.viewed.false'),
            ]);
            DB::commit();

            return true;
        } catch (\Exception $e){
            return false;
        }
    }

    public function countTypes($user_id){
        $query = $this->model();
        $datas = [];
        foreach($this->allTypeValid() as $type){
            $datas[$type] = $query->countByCondition([
                'type' => $type,
                'owner_id' => $user_id
            ]);
        }
        $rejectStatus = $this->rejectStatuses();
        $keyUnset = array_search(config('view.request.returned'), $rejectStatus, true);
        unset($rejectStatus[$keyUnset]);
        array_push($rejectStatus, config('view.request.cancel'));
        $datas[config('view.request.all')] = $query
            ->where('owner_id', $user_id)
            ->whereNotIn('type', $rejectStatus)
            ->count();
        return $datas;
    }

    public function sendNofiticationToUserWaiting($bookRequest)
    {
        $usersWaiting = $this->model()
            ->where([
                'book_id' => $bookRequest->book_id,
                'type' => config('view.request.waiting')
            ])
            ->where('user_id', '!=', $bookRequest->user_id)
            ->select(['owner_id', 'user_id', 'id'])
            ->get()->map(function($bookUser){
                return [
                    'send_id' => $bookUser->owner_id,
                    'receive_id' => $bookUser->user_id,
                    'target_type' => config('model.target_type.book_user'),
                    'target_id' => $bookUser->id,
                    'viewed' => config('model.viewed.false'),
                    'created_at' => now()
                ];
            });
        return $this->notification->model()->insert($usersWaiting->toArray());
    }

    protected function allTypeValid(){
        return [
            config('view.request.waiting'),
            config('view.request.reading'),
            config('view.request.returning'),
            config('view.request.returned'),
            config('view.request.abtExpire'),
        ];
    }

    protected function rejectStatuses(){
        return [
            config('view.request.returned'),
            config('view.request.hasExtended'),
            config('view.request.abtExpire'),
            config('view.request.cancelExtend')
        ];
    }
}
