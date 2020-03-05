<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\OfficeRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\BookRepository;
use App\Repositories\Contracts\ReviewBookRepository;
use App\Repositories\Contracts\UsermetaRepository;
use Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\SearchRequest;

class HomeController extends Controller
{
    protected $book;

    protected $office;

    protected $user;

    protected $review;

    protected $usermeta;

    public function __construct(
        BookRepository $book,
        OfficeRepository $office,
        UserRepository $user,
        ReviewBookRepository $review,
        UsermetaRepository $usermeta
    ) {
        $this->book = $book;
        $this->office = $office;
        $this->user = $user;
        $this->review = $review;
        $this->usermeta = $usermeta;
    }

    public function index()
    {
        $with = [
            'medias',
            'owners.office',
            'countReview',
            'categories',
        ];
        $take = config('view.taking_numb.latest_book');
        
        if (!\Cache::has('offices')) {
            $offices = $this->office->getData()->pluck('name', 'id');
            \Cache::put('offices', $offices, 1440);
        } else {
            $offices = \Cache::get('offices');
        }
        $topViewed = $this->book->getTopViewedBook($with, [], $take);
        $topReview = $this->book->getTopReviewBook([], $take);
        $topInteresting = $this->book->getTopInterestingBook($with, [], $take);
        $latestBook = $this->book->getLatestBook($with, [], $take);
        $bestSharings = $this->book->getBestSharing();
        $officeBooks = $this->book->getOfficeBooks($offices, ['medias']);
        $totalUser = $this->user->countUser();
        $totalBook = $this->book->countBook();
        $totalReview = $this->review->countReview();

        $data = compact(
            'topInteresting',
            'topReview',
            'topViewed',
            'latestBook',
            'bestSharings',
            'officeBooks',
            'totalUser',
            'totalBook',
            'totalReview'
        );

        return view('index', $data);
    }

    public function adminIndex()
    {
        return view('admin.layout.index');
    }

    public function changeLanguage(Request $request, $language)
    {
        if (Auth::check()) {
            $data = [
                'user_id' => Auth::id(),
                'key' => 'website-language',
            ];
            $this->usermeta->settingLanguage(Auth::id(), $language);
            Session::put('website-language', $this->usermeta->find($data)->value);
        } else {
            Session::put('website-language', $language);
        }

        return redirect()->back();
    }

    public function searchAjax(SearchRequest $request)
    {
        if ($request->req != '') {
            $result = collect([
                'titles' => $this->book->searchPage('title', $request->req)->take(config('view.paginate.book_request')),
            ]);
        }

        return view('layout.section.search', compact('result'));
    }

    public function search(SearchRequest $request)
    {
        $with = [
            'owners.office',
            'medias',
        ];
        $data['users'] = $this->user->search('name', $request->req)->paginate(5, ['*'], 'users');
        $data['titles'] = $this->book->search('title', $request->req, 'titles', $with);
        $data['authors'] = $this->book->search('author', $request->req, 'authors', $with);
        $data['descriptions'] = $this->book->search('description', $request->req, 'descriptions', $with);
        $data['key'] = $request->req;
        $data['page'] = trans('page.summary');

        return view('search', $data);
    }

    public function searchPageAjax(SearchRequest $request)
    {
        $data['page'] = $request->type;
        $with = [
            'owners.office',
            'medias',
        ];
        $data['users'] = $this->user->search('name', $request->req)->paginate(config('view.pageUser'), ['*'], 'users');
        $data['titles'] = $this->book->search('title', $request->req, 'titles', $with);
        $data['authors'] = $this->book->search('author', $request->req, 'authors', $with);
        $data['descriptions'] = $this->book->search('description', $request->req, 'descriptions', $with);
        $data['key'] = $request->req;

        return view('layout.section.search_page', $data);
    }

    public function getPhone($phone, $radio)
    {
        $pattern = '/^(\+84|0)\d{9,10}$/';
        if (preg_match($pattern, $phone)) {
            $id = Auth::id();
            $data = [
                'phone' => $phone,
            ];
            $this->user->update($id, $data);
            $this->usermeta->updateDisplayPhone($id, $radio);

            return response()->json([
                'data' => '1',
            ]);
        } else {
            return response()->json([
                'data' => '0',
            ]);
        }
    }
}
