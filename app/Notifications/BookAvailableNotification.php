<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookAvailableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Book $book
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
        return 'book_available';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__("notifications.waiting_list.available_subject", ['title' => $this->book->title]))
            ->greeting(__("notifications.waiting_list.greeting", ['name' => $notifiable->customer->name]))
            ->line(__("notifications.waiting_list.available_db_message", ['title' => $this->book->title]))
            ->line(__("notifications.waiting_list.thanks"));
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
            'title_key' => "notifications.waiting_list.available_subject",
            'message_key' => "notifications.waiting_list.available_db_message",
            'message_params' => [
                'title' => $this->book->title,
            ],
        ];
    }
}
