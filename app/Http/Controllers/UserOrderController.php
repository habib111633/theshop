<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            abort(403);
        }
        // Restore stock for each item in the order
        DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
            $order->status = 'cancelled';
            $order->save();
        });
        // Optionally notify admin here
        return redirect()->route('customer.orders.index')->with('success', 'Order cancelled.');
    }

    public function invoice(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        $pdf->setOption('isRemoteEnabled', true); // ← Critical for images/CSS
        $pdf->setPaper('A4', 'portrait')->setOption('margin-top', 0)
                                        ->setOption('margin-right', 0)
                                        ->setOption('margin-bottom', 0)
                                        ->setOption('margin-left', 0);
        return $pdf->download('invoice-order-' . $order->id . '.pdf');

    }
}
