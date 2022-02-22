<?php

namespace App\Repositories\Interfaces;

use App\Models\RecordData;

interface RecordDataRepositoryInterface
{
    public function save(string $recordId, array $data, int $type, $timestamp): RecordData;
}
