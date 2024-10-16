<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

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
        // 'subcription_user_id',
        // 'is_butterflydance_user',
        'name',
        'email',
        'password',
        'avatar',
        // 'temporary_url_token',
        // 'email_verified_at',
        // 'remember_token',
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
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
