<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\BioRequest;
use App\Http\Requests\UploadCoverRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OwnerRepository;
use App\Repositories\Contracts\BookUserRepository;
use App\Repositories\Contracts\ReputationRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\FollowRepository;
use App\Repositories\Contracts\NotificationRepository;
use App\Repositories\Contracts\UsermetaRepository;
use App\Repositories\Contracts\BookmetaRepository;
use App\Repositories\Contracts\BookRepository;
use App\Eloquent\Owner;
use App\Eloquent\BookUser;
use Auth;

class UserController extends Controller
{
    protected $owner;

    protected $bookUser;

    protected $reputation;

    protected $user;

    protected $follow;

    protected $notification;

    protected $usermeta;

    protected $bookmeta;

    protected $book;

    public function __construct(
        OwnerRepository $owner,
        BookUserRepository $bookUser,
        ReputationRepository $reputation,
        UserRepository $user,
        FollowRepository $follow,
        NotificationRepository $notification,
        UsermetaRepository $usermeta,
        BookmetaRepository $bookmeta,
        BookRepository $book
    ) {
        $this->middleware('auth');
        $this->owner = $owner;
        $this->bookUser = $bookUser;
        $this->reputation = $reputation;
        $this->user = $user;
        $this->follow = $follow;
        $this->notification = $notification;
        $this->usermeta = $usermeta;
        $this->bookmeta = $bookmeta;
        $this->book = $book;
    }

    public function myProfile()
    {
        $user = Auth::user()
            ->load([
                'office',
                'ownerBooks',
                'followers',
                'followings',
                'favorites',
            ]);
            $id = Auth::id();
            $phone = 0;

        return $this->getUserInfo($user, $id, $phone);
    }

    public function postMyProfile($id)
    {
        $selects = [
            'id',
        ];
        $with = [
            'ownerBooks',
        ];
        $user = $this->user->find($id, $with, $selects);
        $books = $user->ownerBooks()->where('user_id', $id)->paginate(config('view.limit.related_book'));

        return view('layout.section.sharing_books', compact('books'));
    }

    public function getBooks($status, $id)
    {
        $user = $this->user->find($id)
            ->load([
                'books',
            ]);
        if ($status === config('view.request.favorite')) {
            $books = $user->favorites();
        } else {
            $books = $user->books()->where('type', $status);
        }
        $books = $books->with(['owners', 'medias'])->paginate(config('view.paginate.book_profile'), ['*'], $status);

        return view('layout.section.profile_books', compact('books', 'status'));
    }

    public function postFollowProfile($status, $id)
    {
        $selects = [
            'id',
        ];
        $followingIds = Auth::user()->followings->pluck('id')->toArray();
        $user = $this->user->find($id, $status, $selects);
        if ($status == 'followers') {
            $followers = $user->followers()->paginate(config('view.paginate.book_request'), ['*'], $status);

            return view('layout.section.follow', compact('followers', 'followingIds'));
        } else {
            $followings = $user->followings()->paginate(config('view.paginate.book_request'), ['*'], $status);

            return view('layout.section.follow', compact('followings', 'followingIds'));
        }
    }

    public function getUserInfo($user, $id, $phone)
    {
        if ($phone == 1) {
            $data = [
                'user_id' => $id,
                'key' => 'display_phone',
            ];
            $phones = $this->usermeta->getData($data);
            if (count($phones) > 0) {
                $phoneUser = $phones->first()->value;
            } else {
                $phoneUser = 1;
            }
        } else {
            $phoneUser = 1;
        }
        $books = $user->ownerBooks()->where('user_id', $id)
                ->with(['owners', 'medias'])
                ->paginate(config('view.limit.related_book'));
        $status = config('view.status.sharing');
        $followingIds = Auth::user()->followings->pluck('id')->toArray();
        $followers = $user->followers()->paginate(config('view.paginate.follow_user'), ['*'], 'followers');
        $followings = $user->followings()->paginate(config('view.paginate.follow_user'), ['*'], 'followings');

        return view('user.profile', compact(
            'user',
            'books',
            'status',
            'followings',
            'followers',
            'followingIds',
            'phoneUser'
        ));
    }

    public function getUser($id)
    {
        try {
            if ($id != Auth::id()) {
                $selects = ['*'];
                $with = [
                    'office',
                    'ownerBooks',
                    'followers',
                    'followings',
                ];
                $phone = 1;
                $user = $this->user->find($id, $with, $selects);

                return $this->getUserInfo($user, $id, $phone);
            } else {
                return redirect()->route('my-profile');
            }
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function sharingBook($id)
    {
        try {
            $with = [
                'office',
            ];
            // save book office in table bookmeta
            $bookOffice = $this->book->find((int)$id, $with)->office;
            $userOffice = Auth::user()->office->name;
            if (!$userOffice) {
                $userOffice = 'Hanoi Office';
            }
            $data['book_id'] = $id;
            foreach ($bookOffice as $office) {
                if ($userOffice != $office->key) {
                    $this->bookmeta->store($data);
                } else {
                    $this->bookmeta->updateBookOffice($office->id);
                }
            }

            //save book owner in table owner
            $result = $this->owner->store([
                'user_id' => Auth::id(),
                'book_id' => $id,
            ]);

            //get avatar user
            $data = Auth::user()->only('id', 'name', 'avatar');
            $data['avatar'] = $data['avatar'];
            $data['office'] = $userOffice;

            if (is_null($data['avatar'])) {
                $data['avatar'] = asset(config('view.image_paths.user') . '1.png');
            }

            // up point for user sharing
            if ($result !== true) {
                $record = $this->reputation->store([
                    'point' => config('model.reputation.share_book'),
                    'user_id' => $data['id'],
                    'target_type' => config('model.target_type.book'),
                    'target_id' => $id,
                ]);
                $point = Auth::user()->reputation_point + $record->point;
                $this->user->update(
                    Auth::id(),
                    [
                        'reputation_point' => $point,
                    ]
                );
            }

            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function removeOwner($id)
    {
        try {
            $with = [
                'office',
            ];
            // delete or update book office in table bookmeta
            $bookOffice = $this->book->find((int)$id, $with)->office;
            $userOffice = Auth::user()->office;

            if (!$userOffice) {
                $userOffice = 'Hanoi Office';
            }
            $data['book_id'] = $id;
            foreach ($bookOffice as $office) {
                if ($userOffice == $office->key) {
                    $this->bookmeta->destroyBookOffice($office->id);
                }
            }

            // delete book request when owner hasn't book
            $this->bookUser->destroy([
                'book_id' => $id,
                'owner_id' => Auth::id(),
            ]);

            // delete owner
            $this->owner->destroy([
                'user_id' => Auth::id(),
                'book_id' => $id,
            ]);

            return Auth::id();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function borrowingBook(Request $request, $id)
    {
        $request->merge([
            'type' => config('model.book_user.type.waiting'),
            'book_id' => $id,
            'user_id' => Auth::id(),
            'approved' => config('model.approved.default'),
        ]);

        $bookUser = $this->bookUser->store($request->all());

        if ($bookUser) {
            $userData = [
                'send_id' => Auth::id(),
                'receive_id' => $bookUser->owner_id,
                'target_type' => config('model.target_type.book_user'),
                'target_id' => $bookUser->id,
                'viewed' => config('model.viewed.false'),
            ];
            $this->notification->store($userData);
        }

        return $bookUser;
    }

    public function cancelBorrowing($bookId)
    {
        try {
            $id = $this->bookUser->destroy([
                'book_id' => $bookId,
                'user_id' => Auth::id(),
            ]);

            if ($id != 0) {
                $this->notification->destroy([
                    'send_id' => Auth::id(),
                    'target_type' => config('model.target_type.book_user'),
                    'target_id' => $id,
                ]);
            }

            return $id;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function follow($id)
    {
        try {
            $result = $this->follow->store([
                'following_id' => $id,
                'follower_id' => Auth::id(),
            ]);

            if ($result !== true) {
                $record = $this->reputation->store([
                    'point' => config('model.reputation.follow'),
                    'user_id' => $id,
                    'target_type' => config('model.target_type.user'),
                    'target_id' => Auth::id(),
                ]);
                $point = $this->user->find($id)->reputation_point + $record->point;
                $this->user->update(
                    $id,
                    [
                        'reputation_point' => $point,
                    ]
                );
                $info = [
                    'send_id' => Auth::id(),
                    'receive_id' => $id,
                    'target_type' => config('model.target_type.follow'),
                    'target_id' => $result->id,
                    'viewed' => config('model.viewed.false'),
                ];
                $this->notification->store($info);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function unfollow($id)
    {
        return $this->follow->destroy([
            'following_id' => $id,
            'follower_id' => Auth::id(),
        ]);
    }

    public function getPrompt()
    {
        return $this->bookUser->getPromptList();
    }

    /**
     * returnBook
     *user submit is returning book
     *type of bookuser -> returning
     * @param  mixed $id
     *id of book
     * @return void
     */
    public function returnBook($id)
    {
        try {
            $this->bookUser->returnBook($id);

            return $id;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateBio(BioRequest $request, $id)
    {
        if (Auth::id() !== (int)$id) {
            return response()->json([
                'status' => false,
                'msg' => trans('page.errBio'),
            ]);
        } else {
            Auth::user()->update([
                'bio' => $request->bio,
            ]);

            return response()->json([
                'status' => true,
                'msg' => trans('page.succBio'),
            ]);
        }
    }

    public function changeCover(UploadCoverRequest $request)
    {
        $user_id = Auth::id();

        return $request->hasFile('cover') ? $this->user->changeCover($request->file('cover'), $user_id) : false;
    }
}
