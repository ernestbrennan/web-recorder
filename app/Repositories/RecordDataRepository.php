<?php

namespace App\Repositories;

use App\Models\RecordData;
use App\Repositories\Interfaces\RecordDataRepositoryInterface;

class RecordDataRepository implements RecordDataRepositoryInterface
{
    public function save(string $recordId, array $data, int $type, $timestamp): RecordData
    {
        $recordData = new RecordData();
        $recordData->record_id = $recordId;
        $recordData->data = $data;
        $recordData->type = $type;
        $recordData->timestamp = $timestamp;

        $recordData->save();

        return $recordData;
    }
}
