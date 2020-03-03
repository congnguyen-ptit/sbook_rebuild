<?php

namespace App\Repositories\Eloquents;

use App\Eloquent\Media;
use App\Repositories\Contracts\MediaRepository;

class MediaEloquentRepository extends AbstractEloquentRepository implements MediaRepository
{
    public function model()
    {
        return new Media;
    }

    public function getData($data = [])
    {
        return $this->model()->all();
    }

    public function store($data = [])
    {
        if (isset($data['avatar'])) {
            $imgBook = $data['avatar'];
            $filename = str_random(4) . '_' . preg_replace('/\s+/', '', $imgBook->getClientOriginalName());
            $imgBook->move(config('view.image_paths.book'), $filename);
        }else{
            $filename = config('view.image_paths.nameDefaultBookImage');
        }
        $data['path'] = $filename;
        $data['target_id'] = $data['book_id'];
        $data['target_type'] = 'App\Eloquent\Book';
        $data['priority'] = 1;
        return $this->model()->create($data);
    }

    public function find($id)
    {
        return $this->model()->findOrFail($id);
    }

    public function clone($data = [])
    {
        return $this->model()->create($data);
    }

    public function destroy($data)
    {
        if (isset($data['avatar_old']) && isset($data['avatar'])) {
            $imgBook = $this->model()->findOrFail($data['avatar_old']);
            if ($imgBook->path != null && file_exists(config('view.image_paths.book') . $imgBook->path)) {
                unlink(config('view.image_paths.book') . $imgBook->path);
            }

            return $imgBook->delete();
        }
    }

    public function updateAndRemoveOldMedia($bookId, $mainImage, $oldImages = [])
    {
        $this->model()->where('target_id', $bookId)->WhereNotIn('id', $oldImages)->delete();
        $this->model()->where('target_id', $bookId)->update(['priority' => Media::TYPE_PRIORITY_SECOND]);
        if ($oldImages && count($oldImages) > 0) {
            foreach ($oldImages as $oldImageId) {
                if ($oldImageId == $mainImage) {
                    $this->find($oldImageId)->update(['priority' => Media::TYPE_PRIORITY_MAIN]);
                }
            }
        }
    }

    public function addImageBook($bookId, $images = [])
    {
        if ($images && count($images) > 0) {
            foreach ($images as $image) {
                $filename = str_random(4) . '_' . preg_replace('/\s+/', '', $image->getClientOriginalName());
                $image->move(config('view.image_paths.book'), $filename);
                $data['path'] = $filename;
                $data['target_id'] = $bookId;
                $data['target_type'] = Media::TYPE_TARGET_BOOK;
                $data['priority'] = Media::TYPE_PRIORITY_SECOND;

                $this->model()->create($data);
            }
        }
    }
}
