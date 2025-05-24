<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AppointmentCheckCancel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-appointment:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $count = Appointment::whereDate('appointment_date', '<', $today)
            ->where('status','pending')
            ->update(['status' => 'cancelled']);

        $this->info("Cancelled {$count} appointments");
    }
}
