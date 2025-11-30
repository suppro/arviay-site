<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!session('user_id') || session('user_role') != 1) {
            return redirect()->route('login');
        }

        $stats = [
            'total_orders' => Order::count(),
            'new_orders' => Order::where('status_id', 1)->count(),
            'active_orders' => Order::whereIn('status_id', [2, 3, 4])->count(),
            'total_users' => User::count()
        ];

        $recent_orders = Order::with(['status', 'user'])
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    public function orders()
    {
        if (!session('user_id') || session('user_role') != 1) {
            return redirect()->route('login');
        }

        $orders = Order::with(['status', 'user', 'items.variant.product'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        $statuses = Status::all();

        return view('admin.orders', compact('orders', 'statuses'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        if (!session('user_id') || session('user_role') != 1) {
            return redirect()->route('login');
        }

        $request->validate([
            'status_id' => 'required|integer|exists:Status,id'
        ]);

        $order = Order::findOrFail($orderId);
        $order->update(['status_id' => $request->status_id]);

        return back()->with('success', 'Статус заказа обновлен!');
    }
}