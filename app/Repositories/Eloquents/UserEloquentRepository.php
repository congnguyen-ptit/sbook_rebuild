<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\User;
use App\Repositories\Contracts\UserRepository;
use File;

class UserEloquentRepository extends AbstractEloquentRepository implements UserRepository
{
    public function model()
    {
        return new User;
    }

    public function getData($with = [], $data = [], $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->get();
    }

    public function find($id, $with = [], $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->findOrFail($id);
    }

    public function store($data = [])
    {
        $data['password'] = bcrypt($data['password']);

        return $this->model()->create($data);
    }

    public function update($id, $data = [])
    {
        $user = $this->model()->findOrFail($id);

        return $user->update($data);
    }

    public function search($attribute, $data, $with = [], $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->with($with)
            ->fullTextSearch($attribute, $data);
    }

    public function destroy($id)
    {
        return $this->model()->findOrFail($id)->delete();
    }

    public function countUser()
    {
        return $this->model()->count();
    }

    public function phoneUser($data, $dataSelect = ['*'])
    {
        return $this->model()
            ->select($dataSelect)
            ->where($data)
            ->get();
    }

    public function likedBook($user, $book_id)
    {
        $liked = $user->favorites()
            ->wherePivot('book_id', $book_id)
            ->count();

        return $liked;
    }

    public function addFavoriteBook($id, $book_id)
    {
        $user = $this->model()->findOrFail($id);
        $liked = $this->likedBook($user, $book_id);
        if ($liked === 0) {
            $user->favorites()->attach($book_id);
            $status = true;
        } else {
            $user->favorites()->detach($book_id);
            $status = false;
        }

        return $status;
    }

    public function changeCover($file, $id)
    {
        try {
            $pathCover = config('view.image_paths.cover');
            $user = $this->model()->findOrFail($id);
            $oldCover = $user->cover;
            $pathImage = $pathCover . $oldCover;
            if (File::exists($pathImage))
                File::delete($pathImage);
            $filename = str_random(10) . '_' . preg_replace('/\s+/', '', $file->getClientOriginalName());
            $file->move($pathCover, $filename);
            $user->update(['cover' => $filename]);

            return [
                'status' => true,
                'data' => $pathCover . $user->cover,
                'message' => trans('settings.profile.changeCoverSucc'),
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'data' => $e->getMessage(),
            ];
        }
    }
}
