<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 * @property string record_id
 * @property string data
 * @property string type
 * @property string timestamp
 */
class RecordData extends Model
{
    protected $table = 'record_data';

    protected $casts = [
        'data' => 'json'
    ];
    protected $fillable = [
        'id',
        'record_id',
        'data',
        'type',
        'timestamp',
    ];

    public $timestamps = false;
}
