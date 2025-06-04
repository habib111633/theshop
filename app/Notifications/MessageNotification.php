<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The message instance.
     *
     * @var mixed
     */
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('You have a new message from ' . $this->message->user->name . ':')
            ->line($this->message->body)
            ->action('View Conversation', url('/conversations/' . $this->message->conversation_id))
            ->line('Thank you for using our application!');
    }
/**
 * Get the array representation of the notification.
 *
 * @return array<string, mixed>
 */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
