<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\BookUser;
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

    public function updateBookUser($type, $id)
    {
        DB::select(
            "UPDATE book_user SET type = '$type' WHERE id = $id"
        );
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
                        break;
                    case config('view.request.reading'):
                        $type = 'returning';
                        $this->updateBookUser($type, $bookRequest->id);
                        break;
                    case config('view.request.returning'):
                        $type = 'returned';
                        $this->updateBookUser($type, $bookRequest->id);
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

    public function getDataRequest($data = [], $with = [], $dataSelect = ['*'], $attribute = ['id', 'desc'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->where($data)
            ->whereHas('book', function ($query) {
                $query->where('deleted_at', null);
            })
            ->orderBy($attribute[0], $attribute[1])
            ->paginate(config('view.paginate.book_request'), ['*'], isset($data['type']) ? $data['type'] : 'page');
    }

    public function getDetailData($request)
    {
        try {
            $with = [
                'user',
            ];

            return $this->getDataRequest($request, $with);
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
                    ->where('type', '<>', config('view.request.returned'))
                    ->orderBy('created_at', 'desc')
                    ->first();
        $type = config('view.request.returning');
        
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
                    ->where('type', '<>', config('view.request.returned'))
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
}
