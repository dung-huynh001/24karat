<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberFieldFormatTrns extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member_field_format_trns';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'member_field_format_trn_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_field_format_master_id',
        'member_field_format_trn_name',
        'member_field_format_trn_value',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'delete_flag',
    //     'created_at',
    //     'updated_at',
    // ];

    /**
     * Relationship with the MemberFieldFormatMasters model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function memberFieldFormatMasters()
    {
        return $this->belongsTo(MemberFieldFormatMasters::class, 'member_field_format_master_id');
    }
}
