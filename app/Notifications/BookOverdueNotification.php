<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Book $book,
        public readonly int $daysLate
    ){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function databaseType(object $notifiable): string
    {
        return 'book_overdue';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__("notifications.overdue.subject", ['title' => $this->book->title]))
            ->greeting(__("notifications.overdue.greeting", ['name' => $notifiable->customer->name]))
            ->line(__("notifications.overdue.message", [
                'title' => $this->book->title,
                'days' => $this->daysLate
            ]))
            ->line(__("notifications.overdue.warning"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'book_id' => $this->book->id,
            'days_late' => $this->daysLate,
            'message_key' => "notifications.overdue.db_message",
            'message_params' => [
                'title' => $this->book->title,
                'days' => $this->daysLate,
            ],
        ];
    }
}
