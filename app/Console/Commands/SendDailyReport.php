<?php

namespace App\Console\Commands;

use App\Mail\DailyReports;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

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
        Mail::to('julius.decena3095@gmail.com')->send(new DailyReports());
        return Command::SUCCESS;
    }
}
