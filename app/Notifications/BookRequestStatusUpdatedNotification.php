<?php

namespace App\Notifications;

use App\Models\BookRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookRequestStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly BookRequest $bookRequest,
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
        return "book_request_{$this->bookRequest->status}";
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $book_display = $this->bookRequest->book_title;

        if($this->bookRequest->book_author){
            $book_display .= " by {$this->bookRequest->book_author}";
        }

        $status = $this->bookRequest->status;

        $message = (new MailMessage)
            ->subject(__("notifications.book_request.status.{$status}.subject", ['title' => $this->bookRequest->book_title]))
            ->greeting(__("notifications.book_request.greeting", ['name' => $notifiable->customer->name]))
            ->line(__("notifications.book_request.status.{$status}.message", ['book_display' => $book_display]));

        if ($this->bookRequest->admin_note) {
            $message->line(__("notifications.book_request.note", ['note' => $this->bookRequest->admin_note]));
        }

        return $message
            ->line(__("notifications.book_request.thanks"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $suffix = $this->bookRequest->author_name ? 'db_message_with_author' : 'db_message';

        return [
            'request_id' => $this->bookRequest->id,
            'status' => $this->bookRequest->status,
            'message_key' => "notifications.request.status.{$this->bookRequest->status}.{$suffix}",
            'message_params' => [
                'title' => $this->bookRequest->book_title,
                'author' => $this->bookRequest->author_name,
            ],
        ];
    }
}
