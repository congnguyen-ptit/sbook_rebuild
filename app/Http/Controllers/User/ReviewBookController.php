<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ReviewBookRepository;
use App\Http\Requests\ReviewRequest;
use App\Repositories\Contracts\BookRepository;
use App\Repositories\Contracts\VoteRepository;
use App\Repositories\Contracts\NotificationRepository;
use App\Repositories\Contracts\BookmetaRepository;
use App\Eloquent\Vote;
use Auth;
use Exception;
use Session;

class ReviewBookController extends Controller
{
    protected $review;

    protected $book;

    protected $bookmeta;

    public function __construct(
        ReviewBookRepository $review,
        BookRepository $book,
        VoteRepository $vote,
        NotificationRepository $notification,
        BookmetaRepository $bookmeta
    ) {
        $this->middleware('auth');
        $this->review = $review;
        $this->book = $book;
        $this->vote = $vote;
        $this->notification = $notification;
        $this->bookmeta = $bookmeta;
    }

    public function create($slug)
    {
        try {
            $id = (int)last(explode('-', $slug));
            $book = $this->book->find($id, [], []);
            $data['book_id'] = $book->id;

            $flag = true;
            if (Auth::check()) {
                $isReview = $this->review->checkReview($data);
                if ($isReview->count() > 0) {
                    $flag = false;
                }
            } else {
                $flag = false;
            }

            return view('book.review', compact('book', 'flag'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function store(ReviewRequest $request, $slug)
    {
        try {
            $id = (int)last(explode('-', $slug));
            $owners = $this->book->find($id, ['owners'])->owners;
            $request->merge(['book_id' => $id]);

            $isReview = $this->review->checkReview($request->all())->toArray();
            if (empty($isReview)) {
                $review = $this->review->store($request->all());
                if ($review && $owners) {
                    foreach ($owners as $owner) {
                        if ($owner->id != auth()->user()->id) {
                            $info = [
                                'send_id' => Auth::id(),
                                'receive_id' => $owner->id,
                                'target_type' => config('model.target_type.review'),
                                'target_id' => $review->id,
                                'viewed' => config('model.viewed.false'),
                            ];
                            $this->notification->store($info);
                        }
                    }
                }
                $data = $this->review->find($id);
                if (isset($data) && $data != null) {
                    $this->book->updateStar($data, $id);
                }

                $this->bookmeta->updateCountReview($id);
                Session::flash('success', trans('settings.success.store'));

                return redirect()->route('books.show', $slug);
            }

            return back();
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('error');
        }
    }

    public function edit($slug, $id)
    {
        try {
            $idBook = (int)last(explode('-', $slug));
            $book = $this->book->find($idBook, [], []);
            $review = $this->review->findOrFail($id);

            return view('book.edit_review', compact('book', 'review'));
        } catch (Exception $e) {
            return view('error');
        }
    }

    public function update(ReviewRequest $request, $slug, $id)
    {
        try {
            $this->review->update($id, $request->all());

            $idBook = (int)last(explode('-', $slug));
            $data = $this->review->find($idBook);
            if (isset($data) && $data != null) {
                $this->book->updateStar($data, $idBook);
            }
            Session::flash('success', trans('settings.success.update'));

            return redirect()->route('books.show', $slug);
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('error');
        }
    }

    public function destroy($slug, $id)
    {
        try {
            $record = $this->review->destroy($id);
            if ($record) {
                $this->notification->destroy([
                    'send_id' => Auth::id(),
                    'target_type' => config('model.target_type.review'),
                    'target_id' => $id,
                ]);
            }

            $idBook = (int)last(explode('-', $slug));
            $data = $this->review->find($idBook);
            if (isset($data) && $data != null) {
                $this->book->updateStar($data, $idBook);
            }

            $this->bookmeta->destroyCountReview($idBook);
            Session::flash('success', trans('settings.success.delete'));

            return back();
        } catch (Exception $e) {
            Session::flash('unsuccess', trans('settings.unsuccess.error', ['messages' => $e->getMessage()]));

            return view('error');
        }
    }

    public function show($slug, $id)
    {
        $idBook = (int)last(explode('-', $slug));
        $book = $this->book->find($idBook, [], []);
        $review = $this->review->findOrFail($id);
        $voteUp = $review->votes->where('status', '=', '1')->count();
        $voteDown = $review->votes->where('status', '=', '-1')->count();
        $voted = $voteUp - $voteDown;

        $flag = false;
        if (Auth::check()) {
            $userId = Auth::user()->id;

            $vote = Vote::where('review_id', '=', $review->id)
                ->where('user_id', '=', $userId)->first();

            if ($vote != null) {
                if ($vote->status == '1') {
                    $flag = config('view.vote.up');
                } elseif ($vote->status == '-1') {
                    $flag = config('view.vote.down');
                } else {
                    $flag = config('view.vote.no');
                }
            }
        }

        return view('book.show_review', compact('review', 'book', 'voted', 'flag'));
    }
}
