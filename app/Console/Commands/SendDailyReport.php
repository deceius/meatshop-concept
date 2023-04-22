<?php

namespace App\Console\Commands;

use App\Mail\DailyReports;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Daily Report';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now()->format('Y-m-d');
        $postRequest = [
            'auth_token' => '50e2a95a98368134-97dde539d4f9799b-d226545ad2ca6d5a',
            'from'=> '20P73aqnAOJIazs8HsYM6A==',
            'type'=> 'text',
            'text' => 'Kumpadres Daily Sales Reports is ready. Login to access the file. ' . url('/download-report?date='.$date)
        ];
        $url = 'https://chatapi.viber.com/pa/post';
        $response = Http::post($url, $postRequest);
        if ($response->ok()) {
            return Command::SUCCESS;
        }

        return Command::FAILURE;

    }
}
