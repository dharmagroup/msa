<?php

use App\Http\Controllers\Modules\Manufacture\Andons;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('repair:start-automatic', function () {
    $andons = new Andons();
    $andons->_create_making_repair_automatic();
    $this->info('Tugas berhasil dijalankan!');
})->purpose('Display an inspiring quote')->everySecond();
