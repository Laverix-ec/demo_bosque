<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to(\Filament\Facades\Filament::getLoginUrl());
});
