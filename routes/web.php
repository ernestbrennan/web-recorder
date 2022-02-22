<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::get('/record/{id}', function ($id) {
    return view('index', ['id' => $id]);
});
