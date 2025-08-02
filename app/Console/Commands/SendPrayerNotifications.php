<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Prayer;
use App\Services\PushNotificationService;
use Carbon\Carbon;

class SendPrayerNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-prayer-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send prayer notifications to users based on their timezone';

    /**
     * Execute the console command.
     */
    public function handle(PushNotificationService $pushNotificationService)
    {
        $this->info('Sending prayer notifications...');

        $users = User::whereNotNull('timezone')->get();
        $timezones = $users->pluck('timezone')->unique();

        foreach ($timezones as $timezone) {
            $now = Carbon::now($timezone);
            $prayerTime = $this->getPrayerTime($now);

            if ($prayerTime) {
                $prayers = Prayer::where('prayer_date', $now->toDateString())
                    ->where('prayer_time', $prayerTime)
                    ->where('status', 'active')
                    ->get();

                if ($prayers->isNotEmpty()) {
                    $usersInTimezone = $users->where('timezone', $timezone);
                    foreach ($usersInTimezone as $user) {
                        foreach ($prayers as $prayer) {
                            $this->sendNotification($pushNotificationService, $user, $prayer);
                        }
                    }
                }
            }
        }

        $this->info('Prayer notifications sent successfully.');
    }

    private function getPrayerTime(Carbon $now)
    {
        $hour = $now->hour;

        if ($hour >= 5 && $hour < 12) {
            return 'morning';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'afternoon';
        } elseif ($hour >= 17 && $hour < 21) {
            return 'evening';
        }

        return null;
    }

    private function sendNotification(PushNotificationService $pushNotificationService, User $user, Prayer $prayer)
    {
        $title = $prayer->title;
        $body = $prayer->description;
        $data = [
            'prayer_id' => $prayer->id,
        ];

        // Assuming you have a device_token field on your User model
        if ($user->device_token) {
            $pushNotificationService->sendNotification($user->device_token, $title, $body, $data);
        }
    }
}
