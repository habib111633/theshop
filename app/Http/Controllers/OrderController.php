<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view orders')->only(['index', 'show']);
        $this->middleware('can:update orders')->only(['edit', 'update']);
        $this->middleware('can:delete orders')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Search by order ID, customer name, or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('billing_name', 'like', "%$search%")
                    ->orWhere('billing_email', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Sort
        $sort      = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);

        $orders = $query->paginate(20)->appends($request->all());

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);
        $order->status = $request->input('status');
        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        $order->orderItems()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
