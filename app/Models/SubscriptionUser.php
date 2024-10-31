<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SubscriptionUser extends Model
{
    use Notifiable;
    protected $table = 'subscription_users';
    protected $primaryKey = 'subscription_user_id';

    public $incrementing = true;

    protected $keyType = 'int';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sub_domain',
        'barcode_type',
        'company_name',
        'zip',
    ];
}