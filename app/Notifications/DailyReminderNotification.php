<?php

namespace App\Notifications;

 use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;
    use NotificationChannels\Fcm\FcmChannel;
    use NotificationChannels\Fcm\FcmMessage;
    use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

    class DailyReminderNotification extends Notification
    {
        use Queueable;

        public function via($notifiable)
        {
            return [FcmChannel::class];
        }

        public function toFcm($notifiable)
        {
            return FcmMessage::create()
                ->setNotification(FcmNotification::create()
                    ->setTitle('Jangan Lupa Catat Transaksi Hari Ini! 📝')
                    ->setBody('Yuk, catat pengeluaran dan pemasukanmu agar keuangan tetap teratur.')
                );
        }
    }
