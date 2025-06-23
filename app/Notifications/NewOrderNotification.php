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

        $mail = new MailMessage();

        if ($isAdmin) {
            $mail->subject('🛒 New Order Placed on ' . config('app.name'))
                ->greeting('Hello ' . ($notifiable->name ?? 'Admin') . ',')
                ->line('A new order has been placed on your store.')
                ->line('Order ID: #' . $this->order->id)
                ->line('Customer: ' . $this->order->user->name . ' (' . $this->order->user->email . ')')
                ->line('Order Total: $' . number_format($this->order->total, 2))
                ->action('View Order Details', route('admin.orders.show', $this->order->id))
                ->line('Thank you for trusting ' . config('app.name') . '!');
        } else {
            $mail->subject('Your Order Confirmation - ' . config('app.name'))
                ->greeting('Thank you for your order, ' . ($notifiable->name ?? '') . '!')
                ->line('We have received your order and are processing it now.')
                ->line('Order ID: #' . $this->order->id)
                ->line('Order Total: $' . number_format($this->order->total, 2))
                ->action('View Your Order', url('/orders/' . $this->order->id))
                ->line('If you have any questions, reply to this email or contact our support team.')
                ->line('Thank you for shopping with ' . config('app.name') . '!');
        }

        // Optionally, add a summary of items
        if ($this->order->orderItems && $this->order->orderItems->count()) {
            $mail->line('Order Summary:');
            foreach ($this->order->orderItems as $item) {
                $mail->line('- ' . $item->product_name . ' x' . $item->quantity . ' ($' . number_format($item->price, 2) . ')');
            }
        }

        return $mail;
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
