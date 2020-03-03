<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\BookRequest;
use App\Repositories\Eloquents\UserEloquentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BookCategoryRepository;
use App\Repositories\Contracts\BookRepository;
use App\Repositories\Contracts\MediaRepository;
use App\Repositories\Contracts\CategoryRepository;
use App\Repositories\Contracts\ReviewBookRepository;
use App\Repositories\Contracts\OwnerRepository;
use App\Repositories\Contracts\OfficeRepository;
use App\Repositories\Contracts\BookUserRepository;
use Auth;
use App\Repositories\Contracts\BookmetaRepository;
use App\Repositories\Contracts\NotificationRepository;
use Session;
use App\Events\ViewBook;
use Event;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends Controller
{
    protected $book;

    protected $category;

    protected $bookCategory;

    protected $media;

    protected $review;

    protected $owner;

    protected $office;

    protected $bookUser;

    protected $notification;

    protected $user;

    protected $with = [
        'medias',
        'categories',
        'owners',
        'reviews',
    ];

    public function __construct(
        BookRepository $book,
        CategoryRepository $category,
        BookCategoryRepository $bookCategory,
        MediaRepository $media,
        OwnerRepository $owner,
        ReviewBookRepository $review,
        OfficeRepository $office,
        BookmetaRepository $bookmeta,
        BookUserRepository $bookUser,
        NotificationRepository $notification,
        UserEloquentRepository $user
    ) {
        $this->book = $book;
        $this->category = $category;
        $this->bookCategory = $bookCategory;
        $this->media = $media;
        $this->review = $review;
        $this->owner = $owner;
        $this->office = $office;
        $this->bookmeta = $bookmeta;
        $this->bookUser = $bookUser;
        $this->notification = $notification;
        $this->user = $user;
        $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update']]);
        $this->middleware('viewed.book', ['only' => ['create']]);
    }

    public function index()
    {
        $with = [
            'medias',
            'owners.office',
            'categories',
            'countReview',
        ];
        $categories = $this->category->getData();
        $offices = $this->office->getData();
        $books = $this->book->getBookPaginate($with);

        return view('book.books', compact('categories', 'offices', 'books'));
    }

    public function getBookCategory($slug)
    {
        $id = last(explode('-', $slug));
        $categories = $this->category->getData();
        $offices = $this->office->getData();
        $books = $this->book->getBookCategory($id)
            ->paginate(config('view.paginate.book'));
        $cate = $this->category->find($id);

        return view('book.books', compact('categories', 'offices', 'books', 'cate'));
    }

    public function getBookOffice($slug)
    {
        $categories = $this->category->getData();
        $offices = $this->office->getData();
        $books = $this->book->getBookOffice($slug);
        $off = $this->office->find($slug);

        return view('book.books', compact('categories', 'offices', 'books', 'off'));
    }

    public function create()
    {
        $categories = $this->category->getData();

        return view('book.add', compact('categories'));
    }

    public function store(BookRequest $request)
    {
        $followers = Auth::user()->followers->pluck('id');
        try {
            //save book
            $slug = str_slug($request->title);
            $request->merge(['slug' => $slug]);
            $book = $this->book->store($request->all());
            $request->merge(['book_id' => $book->id]);
            //save bookmeta
            $this->bookmeta->store($request->all());
            //save categories
            if ($request->has('categories')) {
                $this->bookCategory->storeBookCate($book->id, $request->all());
            }
            //create image
            $this->media->store($request->all());

            $data = [
                'user_id' => Auth::user()->id,
                'book_id' => $book->id,
            ];
            $this->owner->store($data);
            if ($followers) {
                foreach ($followers as $follower) {
                    $info = [
                        'send_id' => Auth::id(),
                        'receive_id' => $follower,
                        'target_type' => config('model.target_type.book'),
                        'target_id' => $book->id,
                        'viewed' => config('model.viewed.false'),
                    ];
                    $this->notification->store($info);
                }
            }
            Session::flash('success', trans('settings.success.store'));

            return redirect()->route('books.show', $book->slug . '-' . $book->id);
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $with = [
                'medias',
                'owners',
            ];
            $id = last(explode('-', $slug));
            $book = $this->book->find($id, $this->with);
            $take = config('view.taking_numb.best_sharing_book');
            $topInteresting = $this->book->getTopInterestingBook($with, [], $take);
            $topBorrowed = $this->book->getTopBorrowed($with, [], $take);
            Event::fire('count.view', $book);

            if (!empty($book)) {
                $slugId = $book->slug . '-' . $book->id;
                $filterYears = $this->book->getYearsFilterArray($book);
                if ($slug == $slugId) {
                    if ($book->categories) {
                        $relatedBookIds = $this->bookCategory->getBooks($book->categories->pluck('id'));
                        $relatedBooks = $this->book->getRelatedBooks($relatedBookIds, ['medias', 'owners']);
                    } else {
                        $relatedBooks = null;
                    }
                    $data['book_id'] = $book->id;
                    $reviews = $this->review->show($data);

                    $flag = true;
                    if (Auth::check()) {
                        $isReview = $this->review->checkReview($data);
                        if ($isReview->count() > 0) {
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }

                    $bookStatus = null;
                    if (Auth::check()) {
                        $userId = Auth::id();
                        $isOwner = in_array($userId, $book->owners->pluck('id')->toArray());
                        $isBooking = in_array($userId, $book->users->pluck('id')->toArray());
                        $bookStatus = $this->bookUser->getBookStatusForUser($id);
                    } else {
                        $isOwner = false;
                        $isBooking = false;
                    }

                    $tmp = false;
                    if (Auth::check()) {
                        $likedBook = $this->user->likedBook(Auth::user(), $book->id);
                        $roles = Auth::user()->roles;
                        if ($roles->count() > 0) {
                            if ($roles->count() > 1) {
                                foreach ($roles as $role) {
                                    if ($role->name == 'Admin' || $role->name == 'Editor') {
                                        $tmp = true;
                                    }
                                }
                            } elseif ($roles[0]->name == 'Admin' || $roles[0]->name == 'Editor') {
                                $tmp = true;
                            }
                        }
                    } else {
                        $likedBook = false;
                    }

                    $bookTypeStatus = $this->bookUser->getTypeBook($book->id);
                    $data = [
                        'book',
                        'relatedBooks',
                        'topInteresting',
                        'topBorrowed',
                        'flag',
                        'reviews',
                        'isOwner',
                        'isBooking',
                        'tmp',
                        'bookStatus',
                        'bookTypeStatus',
                        'filterYears',
                        'likedBook',
                    ];

                    return view('book.book_detail', compact($data));
                }
            }

            return view('error');
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('error');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        try {
            $userId = Auth::user()->id;
            $categories = $this->category->getData();
            $id = last(explode('-', $slug));
            $book = $this->book->find($id, $this->with);
            $checked = $book->categories->pluck('id')->toArray();
            $slugId = $book->slug . '-' . $book->id;
            $owners = $book->owners->pluck('id')->toArray();
            if ($slug == $slugId && in_array($userId, $owners)) {
                return view('book.edit', compact('categories', 'book', 'checked'));
            }

            return view('error');
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('error');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, $slug)
    {
        try {
            //update book
            $id = last(explode('-', $slug));
            $request->merge(['slug' => str_slug($request->title)]);
            $userId = Auth::user()->id;
            $book = $this->book->update($id, $userId, $request->all());
            $request->merge(['book_id' => $book->id]);
            if ($book->id == $id) {
                $book->categories()->detach();
                $this->media->destroy($request->all());
                $this->media->store($request->all());
            } elseif (!$request->hasFile('avatar')) {
                $media = $this->book->find($id)->medias[0];
                $media->target_id = $book->id;
                $this->media->clone($media->toArray());
            } else {
                $this->media->store($request->all());
            }
            if ($request->has('category')) {
                $this->bookCategory->store($request->all());
            }
            Session::flash('success', trans('settings.success.update'));

            return back();
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDetailData(Request $request)
    {
        $followingIds = null;
        $req = [
            'type' => $request->type,
            'book_id' => $request->book_id,
        ];
        $data = $this->bookUser->getDetailData($req);
        $type = $request->type;
        if (Auth::check()) {
            $followingIds = Auth::user()->followings->pluck('id')->toArray();
        }

        return view('layout.section.user_list', compact('data', 'type', 'followingIds'));
    }

    public function statisticBook(Request $request, $id)
    {
        $year = $request->year;
        if (!$year) {
            $year = now()->year;
        }
        $statisticBook = $this->book->getStatisticBook($id, $year);

        return response()->json($statisticBook);

    }

    public function addFavorite(Request $request, $id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $result = $this->user->addFavoriteBook($user->id, $id);
        } else {
            $result = false;
        }

        return response()->json(['status' => $result]);
    }

    public function getModalBook($id) {
        $with = [
            'medias',
            'owners',
        ];
        try {
            $book = $this->book->find($id, $with);

            return view('layout.section.modal', compact('book'));
        } catch (NotFoundHttpException $e) {
            return response()->json(false);
        }


    }

    public function getBookByTitle(Request $request)
    {
        if ($request->req != '') {
            if($request->type){
                try {
                    $book = $this->book->find($request->req);

                    return view('layout.section.modal_book_preview', compact('book'));
                }catch (\Exception $e){
                    return response()->json(false);
                }

            }else{
                return response()->json(
                    $this->book->searchPage('title', $request->req)
                         ->take(config('view.paginate.book_request'))
                );
            }
        }
    }
}
