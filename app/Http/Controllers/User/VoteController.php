<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ReviewBookRepository;
use App\Repositories\Contracts\VoteRepository;
use App\Repositories\Contracts\NotificationRepository;
use Auth;

class VoteController extends Controller
{
    protected $vote;

    protected $review;

    protected $notification;

    public function __construct(
        ReviewBookRepository $review,
        VoteRepository $vote,
        NotificationRepository $notification
    ) {
        $this->middleware('auth');
        $this->review = $review;
        $this->vote = $vote;
        $this->notifiaction = $notification;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $review = $this->review->findOrFail($id);

        if (Auth::check()) {
            $userId = Auth::user()->id;
        } else {
            return config('view.vote.login');
        }

        $data = [];
        if ($request->vote == 1) {
            if ($vote = $this->vote->voteUp($review)) {
                if ($vote->status == '0') {
                    $data = ['noup'];
                    $this->notifiaction->destroy([
                        'send_id' => Auth::id(),
                        'receive_id' => $review->user_id,
                        'target_type' => config('model.target_type.vote'),
                        'target_id' => $vote->id,
                    ]);
                } else {
                    $data = ['up'];
                    $this->notifiaction->store([
                        'send_id' => Auth::id(),
                        'receive_id' => $review->user_id,
                        'target_type' => config('model.target_type.vote'),
                        'target_id' => $vote->id,
                        'viewed' => config('model.viewed.false'),
                    ]);
                }
                $this->review->upVote($id, $data);
                $data = $this->vote->getDataVote($review->id, $data[0]);
            } else {
                $data = ['error'];
            }
        } else {
            if ($vote = $this->vote->voteDown($review)) {
                if ($vote->status == '0') {
                    $data = ['nodown'];
                    $this->notifiaction->destroy([
                        'send_id' => Auth::id(),
                        'receive_id' => $review->user_id,
                        'target_type' => config('model.target_type.vote'),
                        'target_id' => $vote->id,
                    ]);
                } else {
                    $data = ['down'];
                    $this->notifiaction->store([
                        'send_id' => Auth::id(),
                        'receive_id' => $review->user_id,
                        'target_type' => config('model.target_type.vote'),
                        'target_id' => $vote->id,
                        'viewed' => config('model.viewed.false'),
                    ]);
                }
                $this->review->downVote($id, $data);
                $data = $this->vote->getDataVote($review->id, $data[0]);
            } else {
                $data = ['error'];
            }
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
