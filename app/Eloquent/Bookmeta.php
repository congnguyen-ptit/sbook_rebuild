<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Bookmeta extends Model
{
    protected $table = 'bookmeta';

    protected $fillable = [
        'key',
        'value',
        'book_id',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
