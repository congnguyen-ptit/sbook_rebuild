<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\Office;
use App\Eloquent\User;
use App\Repositories\Contracts\OfficeRepository;

class OfficeEloquentRepository extends AbstractEloquentRepository implements OfficeRepository
{
    public function model()
    {
        return new Office;
    }

    public function getData($data = [], $with = [], $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->get();
    }

    public function find($slug)
    {
        $offices = Office::all();
        foreach ($offices as $office) {
            if ($slug == str_slug($office->name)) {
                $data = $office->name;
            }
        }
        return isset($data) ? $data : abort(404);
    }

    public function findById($id)
    {
        return $this->model()->findOrFail($id);
    }

    public function store($data = [])
    {
        $office = $this->model()->create($data);
        \Cache::put('offices', $this->getData()->pluck('name', 'id'), 1440);

        return $office;
    }

    public function byName($name)
    {
        return $this->model()->where('name', '=', $name)->first();
    }
}
