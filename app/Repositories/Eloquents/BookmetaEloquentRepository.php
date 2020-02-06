<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\Bookmeta;
use App\Repositories\Contracts\BookmetaRepository;
use Illuminate\Support\Facades\Auth;
use App\Eloquent\Book;

class BookmetaEloquentRepository extends AbstractEloquentRepository implements BookmetaRepository
{
    public function model()
    {
        return new Bookmeta;
    }

    public function getData($data = [], $with = [], $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->where($data)
            ->get();
    }

    public function store($data)
    {
        $office = Auth::user()->office;
        $data['value'] = 1;
        if ($office) {
            $data['key'] = $office->name;
        } else {
            $data['key'] = 'Hanoi Office';
        }

        return $this->model()->create($data);
    }

    public function find($id)
    {
        return $this->model()->firstOrFail($id);
    }

    public function updateBookOffice($id)
    {
        try {
            $bookOffice = $this->model()->find($id);
            $data['value'] = $bookOffice->value + 1;

            return $this->model()->update($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroyBookOffice($id)
    {
        try {
            $bookOffice = $this->model()->find($id);

            if ($bookOffice->value == 1) {
                return $bookOffice->delete();
            } elseif ($bookOffice->value > 1) {
                $data['value'] = $bookOffice->value - 1;

                return $this->model()->update($data);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateCountReview($id)
    {
        $countReview = $this->model()->where('book_id', $id)->where('key', 'count_review')->first();
        $data['key'] = 'count_review';

        if ($countReview) {
            $data['value'] = $countReview->value + 1;

            return $countReview->update($data);
        } else {
            $data['value'] = 1;
            $data['book_id'] = $id;

            return $this->model()->create($data);
        }
    }

    public function destroyCountReview($id)
    {
        try {
            $countReview = $this->model()->where('book_id', $id)->where('key', 'count_review')->first();
            if ($countReview) {
                return $countReview->delete();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateReturned($id, $data)
    {
        return $this->model()->where('book_id', $id)->update($data);
    }

    public function insertReturned($data)
    {
        return $this->model()
            ->create($data);
    }

    public function findReturned($id)
    {
        return $this->model()
            ->select('*')
            ->where([
                ['book_id', '=', $id],
                ['key', '=', 'count_returned'],
            ])
            ->first();
    }
}
