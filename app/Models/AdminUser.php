<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;


class AdminUser extends Authenticatable
{
    use Notifiable;
    protected $table = 'admin_users';
    protected $primaryKey = 'admin_user_id';

    public $incrementing = true;

    protected $keyType = 'int';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subscription_user_id',
        'is_butterflydance_user',
        'name',
        'email',
        'password',
        'avatar',
        'temporary_url_token',
        'email_verified_at',
        'remember_token',
        'delete_flag',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscriptionUser()
    {
        return $this->belongsTo(SubscriptionUser::class, 'subscription_user_id', 'subscription_user_id');
    }

    public function getAvatarUrl()
    {
        return $this->avatar ? Storage::url($this->avatar) : asset('/assets/images/default-user.png');
    }
}
