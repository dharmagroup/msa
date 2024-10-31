<?php

namespace App\Console\Commands;

use App\Http\Controllers\Modules\Manufacture\Andons;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class StartRepairAutomatic extends Command
{
    protected $signature = 'repair:start-automatic';
    protected $description = 'Start automatic repair';

    public function handle()
    {
        $andons = new Andons();
        $andons->_create_making_repair_automatic();
        $this->info('Tugas berhasil dijalankan!');
    }
}
