<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FullTextSearch;

class Book extends Model
{
    use SoftDeletes, FullTextSearch;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'author',
        'total_pages',
        'avg_star',
        'sku',
        'count_viewed',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'owners')->wherePivot('deleted_at', null);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('type', 'approved', 'owner_id', 'created_at', 'days_to_read');
    }

    public function waitingList()
    {
        return $this->users()->wherePivot('type', 'waiting');
    }

    public function bookmetas()
    {
        return $this->hasMany(Bookmeta::class);
    }

    public function updateBooks()
    {
        return $this->hasMany(UpdateBook::class);
    }

    public function medias()
    {
        return $this->morphMany(Media::class, 'target')
            ->orderBy('priority', 'asc')
            ->select([
                'id',
                'target_id',
                'path',
                'priority',
            ]);
    }

    public function image()
    {
        return $this->morphOne(Media::class, 'target')->orderBy('priority', 'desc')->first();
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'target');
    }

    public function bookUser()
    {
        return $this->hasMany(BookUser::class);
    }

    public function countReview()
    {
        return $this->bookmetas()->where('key', 'count_review');
    }

    public function office()
    {
        return $this->bookmetas()->where('key', '<>', 'count_review');
    }

    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }
}
