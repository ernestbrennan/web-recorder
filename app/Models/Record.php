<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 * @property string record_id
 */
class Record extends Model
{
    protected $table = 'record';

    protected $fillable = [
        'id',
        'record_id',
    ];
}
