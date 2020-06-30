<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }

    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }

    // お気に入りしているかしていないかの確認
    public function is_favoriting($micropost_id)
    {
        return $this->favorites()->where('micropost_id', $micropost_id)->exists();
    }

    // お気に入り登録できるようにする際の定義
    public function favorite($micropost_id)
    {
        // 既にお気に入りしているかの確認
        $exist = $this->is_favoriting($micropost_id);

        if ($exist) {
            // 既にお気に入りしていれば何もしない
            return false;
        } else {
            // お気に入りしていなければお気に入りする
            $this->favorites()->attach($micropost_id);
            return true;
        }
    }

    // お気に入り登録解除できるようにする際の定義
    public function unfavorite($micropost_id)
    {
        // 既にお気に入りしているかの確認
        $exist = $this->is_favoriting($micropost_id);

        if ($exist) {
            // 既にお気に入り登録していれば解除する
            $this->favorites()->detach($micropost_id);
            return true;
        } else {
            // まだお気に入りしていなければ何もしない
            return false;
        }
    }
}