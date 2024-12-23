<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MemberFields extends Model
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member_fields';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
        'member_field_format_trn_id',
        'field_name',
        'field_value',
        'field_validation',
        'field_config',
        'used_by',
        'csv_input_rule',
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
     * Relationship with the MemberFieldFormatTrns model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function memberFieldFormatTrns()
    {
        return $this->belongsTo(MemberFieldFormatTrns::class, 'member_field_format_trn_id');
    }

}
