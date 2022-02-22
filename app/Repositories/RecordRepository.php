<?php

namespace App\Repositories;

use App\Models\Record;
use App\Repositories\Interfaces\RecordRepositoryInterface;
use Illuminate\Support\Str;

class RecordRepository implements RecordRepositoryInterface
{
    public function create(): Record
    {
        $record = new Record();

        $record->record_id = strtolower(Str::random(15));

        $record->save();

        return $record;
    }
}
