<?php

namespace App\Eloquent;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\FullTextSearch;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, FullTextSearch;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'employee_code',
        'position',
        'reputation_point',
        'avatar',
        'workspace',
        'office_id',
        'chatwork_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function ownerBooks()
    {
        return $this->belongsToMany(Book::class, 'owners')
            ->wherePivot('deleted_at', null);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class)
            ->withPivot('type', 'approved', 'owner_id', 'created_at', 'days_to_read');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function vote()
    {
        return $this->hasOne(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reputations()
    {
        return $this->hasMany(Reputation::class);
    }

    public function updateBooks()
    {
        return $this->hasMany(UpdateBook::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follow_user', 'following_id', 'follower_id')
            ->wherePivot('deleted_at', null);
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follow_user', 'follower_id', 'following_id')
            ->wherePivot('deleted_at', null);
    }

    public function sendingNotifications()
    {
        return $this->hasMany(Notification::class, 'send_id');
    }

    public function receivingNotifications()
    {
        return $this->hasMany(Notification::class, 'receive_id');
    }

    public static function getRoles($id)
    {
        return static::findOrFail($id)->roles->pluck('name', 'id')->toArray();
    }

    public function usermeta()
    {
        return $this->hasMany(Usermeta::class);
    }
}
