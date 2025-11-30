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
            'completed_orders' => Order::where('status_id', 5)->count(),
            'cancelled_orders' => Order::where('status_id', 6)->count(),
            'total_users' => User::count()
        ];

        $recent_orders = Order::with(['status', 'user'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    public function orders(Request $request)
    {
    if (!session('user_id') || session('user_role') != 1) {
        return redirect()->route('login');
    }

    $query = Order::with(['status', 'user', 'items.variant.product']);

    // Фильтрация по статусу
    if ($request->has('status') && $request->status != 'all') {
        $query->where('status_id', $request->status);
    }

    // Поиск по ID заказа или имени клиента
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhereHas('user', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
              });
        });
    }

    $orders = $query->orderBy('created_at', 'desc')->get();
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
        $oldStatus = $order->status->name;
        $order->update(['status_id' => $request->status_id]);

        return back()->with('success', "Статус заказа #{$orderId} изменен с '{$oldStatus}' на '{$order->fresh()->status->name}'");
    }

    public function orderDetail($id)
    {
    if (!session('user_id') || session('user_role') != 1) {
        return redirect()->route('login');
    }

    $order = Order::with(['status', 'user', 'items.variant.product'])
                 ->findOrFail($id);

    return view('admin.order-detail', compact('order'));
    }
}