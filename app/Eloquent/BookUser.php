<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{

    CONST CANT_BORROW = 0;

    protected $table = 'book_user';

    protected $fillable = [
        'type',
        'approved',
        'days_to_read',
        'owner_id',
        'book_id',
        'user_id',
        'expire',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'target');
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public static function countByCondition ($condition, array $types) :int {
        return static::where($condition)
                    ->booksIntypes($types)
                    ->count();
    }

    public function scopeBooksInTypes($query, array $types){
        return $types ? $query->whereIn('type', $types) : $query;
    }
}
