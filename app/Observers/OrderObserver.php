<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Reload the order with its relationships to ensure order items are available
        $order->load(['orderItems', 'user']);
        // Decrement stock for each product in the order
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }
        // Send notification to admin
        $admin = \App\Models\User::where('is_admin', true)->first();
        if ($admin) {
            $admin->notify(new \App\Notifications\NewOrderNotification($order));
        }
        // Send notification to customer if authenticated and not admin
        if (\Illuminate\Support\Facades\Auth::check()) {
            \Illuminate\Support\Facades\Auth::user()->notify(new \App\Notifications\NewOrderNotification($order));
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // If status changed to cancelled, restore stock
        if ($order->isDirty('status') && $order->status === 'cancelled') {
            $order->load('orderItems');
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }
        // If order items changed (quantity updated), adjust stock accordingly
        // (Advanced: would require tracking previous quantities, not implemented here)
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // If order is not cancelled, restore stock
        if ($order->status !== 'cancelled') {
            $order->load('orderItems');
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
