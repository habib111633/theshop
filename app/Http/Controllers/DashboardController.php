<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Sales for the last 7 days
        $salesData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labe3ls[] = $date->format('D');
            $salesData[] = Order::whereDate('created_at', $date)->sum('total');
        }

        // Sales summary
        $today = Carbon::today();
        $salesToday = Order::whereDate('created_at', $today)->sum('total');
        $salesMonth = Order::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->sum('total');
        $salesTotal = Order::sum('total');

        // Orders by status
        $ordersByStatus = [
            'Pending' => Order::where('status', 'pending')->count(),
            'Processing' => Order::where('status', 'processing')->count(),
            'Completed' => Order::where('status', 'completed')->count(),
            'Cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Users
        $usersTotal = User::count();
        $usersNewThisMonth = User::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->count();

        // Products
        $productsTotal = Product::count();
        $productsLowStock = Product::where('stock', '<', 10)->count();

        // Recent Orders
        $recentOrders = Order::latest()->take(5)->get();

        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json([
                'salesLabels' => $labels,
                'salesData' => $salesData,
                'ordersByStatusLabels' => array_keys($ordersByStatus),
                'ordersByStatusData' => array_values($ordersByStatus),
                'salesToday' => $salesToday,
                'salesMonth' => $salesMonth,
                'salesTotal' => $salesTotal,
                'ordersByStatus' => $ordersByStatus,
                'usersTotal' => $usersTotal,
                'usersNewThisMonth' => $usersNewThisMonth,
                'productsTotal' => $productsTotal,
                'productsLowStock' => $productsLowStock,
                'recentOrders' => $recentOrders,
            ]);
        }

        // For normal requests, render the view
        return view('dashboard', [
            'salesLabels' => $labels,
            'salesData' => $salesData,
            'ordersByStatus' => $ordersByStatus,
            'salesToday' => $salesToday,
            'salesMonth' => $salesMonth,
            'salesTotal' => $salesTotal,
            'usersTotal' => $usersTotal,
            'usersNewThisMonth' => $usersNewThisMonth,
            'productsTotal' => $productsTotal,
            'productsLowStock' => $productsLowStock,
            'recentOrders' => $recentOrders,
        ]);
    }
} 