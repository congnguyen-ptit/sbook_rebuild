<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\Review;
use App\Repositories\Contracts\ReviewBookRepository;
use Auth;

class ReviewBookEloquentRepository extends AbstractEloquentRepository implements ReviewBookRepository
{
    public function model()
    {
        return new Review;
    }

    public function store($data = [])
    {
        $data['user_id'] = Auth::user()->id;

        return $this->model()->create($data);
    }

    public function show($data = [], $with = ['user'])
    {
        $review = Review::where('book_id', '=', $data)
            ->orderBy('created_at', 'desc')
            ->with($with)
            ->paginate(config('view.paginate.review'), ['*'], 'review');

        return $review;
    }


    public function find($data)
    {
        $review = Review::where('book_id', '=', $data)->get();

        return $review;
    }

    public function findOrFail($id)
    {
        return $this->model()->findOrFail($id);
    }

    public function checkReview($data)
    {
        $data['user_id'] = Auth::user()->id;
        $isReview = Review::where('user_id', '=', $data['user_id'])
            ->where('book_id', '=', $data['book_id'])
            ->get();

        return $isReview;
    }

    public function update($id, $data = [])
    {
        $model = $this->model()->findOrFail($id);
        $data['content'] = strip_tags($data['content'], config('view.text'));

        return $model->update($data);
    }

    public function destroy($id)
    {
        $review = $this->model()->findOrFail($id);

        return $review->delete();
    }

    public function upVote($id, $data)
    {
        $model = $this->model()->findOrFail($id);
        if ($data[0] == 'up') {
            $data['upvote'] = $model->upvote + 1;

            return $model->update($data);
        } elseif ($data[0] == 'noup') {
            $data['downvote'] = $model->downvote - 1;

            return $model->update($data);
        }
    }

    public function downVote($id, $data)
    {
        $model = $this->model()->findOrFail($id);
        if ($data[0] == 'down') {
            $data['downvote'] = $model->downvote + 1;

            return $model->update($data);
        } elseif ($data[0] == 'nodown') {
            $data['upvote'] = $model->upvote - 1;

            return $model->update($data);
        }
    }

    public function getData($with = [], $data = [], $dataSelect = ['*'], $attribute = ['id', 'desc'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->orderBy($attribute[0], $attribute[1])
            ->get();
    }

    public function countReview()
    {
        return $this->model()->count();
    }
}
