<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['You have logged in'];
});

require __DIR__ . '/auth.php';
