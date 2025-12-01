<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Проверка прав доступа (используем role вместо role_id)
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $stats = [
            'total_orders' => Order::count(),
            'new_orders' => Order::where('status', 'new')->count(),
            'active_orders' => Order::whereIn('status', ['new', 'processing'])->count(),
            'total_users' => User::count()
        ];

        $recent_orders = Order::with(['user', 'items'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }

    public function orders(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $query = Order::with(['user', 'items.product']);

        // Фильтрация по статусу
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Поиск по ID заказа или имени клиента
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereJsonContains('customer_details->name', $search)
                  ->orWhereJsonContains('customer_details->phone', $search);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        
        // Статусы для фильтра
        $statuses = [
            'all' => 'Все',
            'new' => 'Новые',
            'processing' => 'В обработке',
            'completed' => 'Выполненные',
            'cancelled' => 'Отмененные'
        ];

        return view('admin.orders', compact('orders', 'statuses'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $request->validate([
            'status' => 'required|in:new,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($orderId);
        $oldStatus = $this->getStatusLabel($order->status);
        $order->update(['status' => $request->status]);

        return back()->with('success', "Статус заказа #{$orderId} изменен с '{$oldStatus}' на '{$this->getStatusLabel($order->status)}'");
    }

    public function orderDetail($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $order = Order::with(['user', 'items.product'])
                     ->findOrFail($id);

        return view('admin.order-detail', compact('order'));
    }
    
    private function getStatusLabel($status)
    {
        $labels = [
            'new' => 'Новый',
            'processing' => 'В обработке',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменен'
        ];
        
        return $labels[$status] ?? $status;
    }
}