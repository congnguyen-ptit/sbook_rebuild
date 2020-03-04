<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getAll()
    {
        return static::select('id', 'name')->get();
    }
}
