<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'priority',
        'public',
    ];

    public function medias()
    {
        return $this->morphMany(Media::class, 'target');
    }
}
