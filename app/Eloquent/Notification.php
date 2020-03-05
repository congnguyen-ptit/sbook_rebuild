<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Notification extends Model
{
    protected $fillable = [
        'send_id',
        'receive_id',
        'target_type',
        'target_id',
        'viewed',
    ];

    public function userSend()
    {
        return $this->belongsTo(User::class, 'send_id');
    }

    public function userReceive()
    {
        return $this->belongsTo(User::class, 'receive_id');
    }

    public function target()
    {
        return $this->morphTo();
    }

    public static function countNew($id)
    {
        $where = [
            'receive_id' => $id,
            'viewed' => 0,
        ];

        return static::where($where)->where('send_id', '!=', $id)->count();
    }
}
