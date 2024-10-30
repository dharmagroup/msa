<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class StartRepairAutomatic extends Command
{
    protected $signature = 'repair:start-automatic';
    protected $description = 'Start automatic repair';

    public function handle()
    {
        // Ganti URL ini dengan URL rute yang sesuai
        $response = Http::post('https://msa-be.dharmagroup.co.id/api/manufacture/start-repair-automatic');
        if ($response->successful()) {
            $this->info('Automatic repair started successfully.');
        } else {
            $this->error('Failed to start automatic repair.');
        }
    }
}
