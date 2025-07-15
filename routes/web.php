<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
//use Illuminate\Support\Facades\Route;

Route::view('/rules/create', 'rules.create')->name('rules.create');
//Route::view('/rules', 'rules.index')->name('rules.index');
