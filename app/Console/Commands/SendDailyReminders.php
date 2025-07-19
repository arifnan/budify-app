<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
    use App\Models\User;
    use App\Notifications\DailyReminderNotification;
    use Illuminate\Support\Facades\Notification;

    class SendDailyReminders extends Command
    {
        protected $signature = 'reminders:send-daily';
        protected $description = 'Send daily transaction reminders to all users.';

        public function handle()
        {
            $this->info('Sending daily reminders...');
            
            // Ambil semua user yang memiliki FCM token
            $users = User::whereNotNull('fcm_token')->get();

            if ($users->isEmpty()) {
                $this->info('No users with device tokens found.');
                return 0;
            }

            // Kirim notifikasi ke setiap user
            Notification::send($users, new DailyReminderNotification());

            $this->info("Successfully sent reminders to {$users->count()} users.");
            return 0;
        }
    }
    
