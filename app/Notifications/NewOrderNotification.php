<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $isAdmin = $notifiable->is_admin ?? false;
        $order = $this->order;
        $user = $order->user;
        $orderItems = $order->orderItems;
        $appName = config('app.name');
        $view = $isAdmin ? 'emails.orders.admin' : 'emails.orders.customer';
        $subject = $isAdmin
            ? '🛒 New Order Placed on ' . $appName
            : 'Your Order Confirmation - ' . $appName;

        return (new MailMessage)
            ->subject($subject)
            ->view($view, [
                'order' => $order,
                'user' => $user,
                'orderItems' => $orderItems,
                'isAdmin' => $isAdmin,
                'appName' => $appName,
                'notifiable' => $notifiable,
            ]);
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
