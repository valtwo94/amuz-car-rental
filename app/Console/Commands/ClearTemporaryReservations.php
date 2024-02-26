<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearTemporaryReservations extends Command
{
    protected $signature = 'reservations:clear-temporary';
    protected $description = 'Clear temporary reservations older than 5 minutes';

    public function handle()
    {
        $expiredReservations = Reservation::where('status', 'temporary')
            ->where('confirmation_time', '<', Carbon::now())
            ->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->delete();
        }

        $this->info('확정 예약 안된 예약이 성공적으로 삭제되었습니다.');
    }
}
