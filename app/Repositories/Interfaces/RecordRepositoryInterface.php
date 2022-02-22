<?php

namespace App\Repositories\Interfaces;

use App\Models\Record;

interface RecordRepositoryInterface
{
    public function create(): Record;
}
