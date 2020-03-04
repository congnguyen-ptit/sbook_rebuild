<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BookRequest;
use App\Repositories\Contracts\BookRepository;
use App\Repositories\Contracts\CategoryRepository;
use App\Repositories\Contracts\MediaRepository;
use App\Repositories\Contracts\BookCategoryRepository;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\DataTables;
use Exception;
use Auth;
use App\Repositories\Contracts\OwnerRepository;
use Session;

class BookController extends Controller
{
    protected $book;

    protected $media;

    protected $bookCategory;

    protected $owner;

    protected $category;

    public function __construct(
        BookRepository $book,
        MediaRepository $media,
        BookCategoryRepository $bookCategory,
        CategoryRepository $category,
        OwnerRepository $owner
    ) {
        $this->book = $book;
        $this->media = $media;
        $this->bookCategory = $bookCategory;
        $this->category = $category;
        $this->owner = $owner;
    }

    public function index()
    {
        $categories = $this->category->getData();

        return view('admin.book.list', compact('categories'));
    }

    public function ajaxShow()
    {
        try {
            $limit = $this->book->countBook();
            $books = $this->book->getData(
                [],
                [],
                ['id', 'title', 'author', 'total_pages', 'avg_star', 'count_viewed', 'slug'],
                ['id', 'desc'],
                $limit
            );

            return Datatables::of($books)
                ->make(true);
        } catch (Exception $e) {
            return view('admin.error.error');
        }
    }

    public function show($id)
    {
        try {
            $book = $this->book->find($id);

            return response()->json($book);
        } catch (Exception $exception) {
            return response()->json(false);
        }
    }

    public function create()
    {

    }

    public function store(BookRequest $request)
    {
        $slug = str_slug($request->title);
        $request->merge(['slug' => $slug]);
        $request->merge(['category' => $request->categories]);
        $data = $request->only([
            'title',
            'description',
            'author',
            'categories',
            'category',
            'total_pages',
            'slug',
        ]);

        DB::beginTransaction();
        try {
            $book = $this->book->store($data);

            if ($request->has('category')) {
                $this->bookCategory->storeBookCate($book->id, $data);
            }

            $this->media->addImageBook($book->id, $request->newImages);

            $data = [
                'user_id' => Auth::user()->id,
                'book_id' => $book->id,
            ];
            $this->owner->store($data);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            return redirect()->route('admin.books.index')->withErrors([$exception->getMessage()]);
        }

        return redirect()->route('admin.books.index')->with('success', trans('admin.success'));
    }

    public function edit($id)
    {
        try {
            $book = $this->book->find($id);
            $categories = $this->category->getData();

            return view('admin.book.edit', compact('book', 'categories'));
        } catch (Exception $exception) {
            return redirect()->route('admin.books.index')->withErrors([trans('admin.validate.book_notExists')]);
        }
    }

    public function update(BookRequest $request, $id)
    {
        $slug = str_slug($request->title);
        $request->merge(['slug' => $slug]);
        $request->merge(['category' => $request->categories]);
        $data = $request->only([
            'title',
            'description',
            'author',
            'categories',
            'category',
            'total_pages',
            'slug',
        ]);

        DB::beginTransaction();
        try {
            $book = $this->book->updateBook($id, $data);

            $book->categories()->detach();
            if ($request->has('category')) {
                $this->bookCategory->storeBookCate($book->id, $data);
            }

            $this->media->updateAndRemoveOldMedia($book->id, $request->main_image, $request->old_image);
            $this->media->addImageBook($book->id, $request->newImages);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            return redirect()->route('admin.books.index')->withErrors([$exception->getMessage()]);
        }

        return redirect()->route('admin.books.index')->with('success', trans('admin.success'));
    }

    public function destroy($id)
    {
        try {
            $book = $this->book->find($id);
            $book->bookmetas()->delete();
            //remote category
            $book->categories()->detach();
            //remove image
            if (isset($book->medias[0])) {
                $this->media->destroy($book);
                $this->book->destroy($id);
            } else {
                $book->delete();
            }

            return response()->json(true);
        } catch (Exception $e) {
            return response()->json(false);
        }
    }
}
