@php
    use App\Models\Order;
@endphp

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление заказами — Вжух! Пицца</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <!-- Админ-шапка -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="text-3xl font-bold">Вжух! Админ</a>
                <nav class="hidden md:flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200 font-medium">Дашборд</a>
                    <a href="{{ route('admin.orders') }}" class="bg-blue-500 text-white px-3 py-1 rounded font-medium">Заказы</a>
                </nav>
            </div>
            <div class="flex items-center gap-6">
                <span class="hidden md:block">Админ: {{ session('user_name') }}</span>
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-bold hover:bg-gray-100">
                    Клиентская часть
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-700">Выйти</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <!-- Заголовок -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold">Управление заказами</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Фильтры и поиск -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form action="{{ route('admin.orders') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Статус заказа</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">Все статусы</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Поиск</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="ID заказа, имя или телефон">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium w-full">
                        Применить фильтры
                    </button>
                </div>
            </form>
        </div>

        <!-- Список заказов -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Клиент</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Телефон</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Адрес</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Статус</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Сумма</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Дата</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 {{ request('highlight') == $order->id ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-semibold">#{{ $order->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->items->count() }} товаров</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $order->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $order->user->phone }}</td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs truncate" title="{{ $order->delivery_address }}">
                                        {{ $order->delivery_address }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <select name="status_id" 
                                                onchange="this.form.submit()"
                                                class="text-sm border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500
                                                    @if($order->status_id == 1) bg-yellow-100 text-yellow-800 border-yellow-300
                                                    @elseif($order->status_id == 5) bg-green-100 text-green-800 border-green-300
                                                    @elseif($order->status_id == 6) bg-red-100 text-red-800 border-red-300
                                                    @else bg-blue-100 text-blue-800 border-blue-300 @endif">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 font-semibold">{{ number_format($order->total_price, 0, ',', ' ') }} ₽</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">{{ date('d.m.Y', strtotime($order->created_at)) }}</div>
                                    <div class="text-xs text-gray-500">{{ date('H:i', strtotime($order->created_at)) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.order.detail', $order->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        Подробнее
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Заказов не найдено</h3>
                    <p class="text-gray-600">Попробуйте изменить параметры фильтрации</p>
                </div>
            @endif
        </div>

        <!-- Статистика по статусам -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-6 gap-4">
            @php
                $statusStats = [
                    1 => ['name' => 'Новые', 'color' => 'bg-yellow-500'],
                    2 => ['name' => 'Приняты', 'color' => 'bg-blue-500'],
                    3 => ['name' => 'Готовятся', 'color' => 'bg-purple-500'],
                    4 => ['name' => 'В доставке', 'color' => 'bg-indigo-500'],
                    5 => ['name' => 'Доставлены', 'color' => 'bg-green-500'],
                    6 => ['name' => 'Отменены', 'color' => 'bg-red-500'],
                ];
            @endphp
            
            @foreach($statusStats as $id => $stat)
                @php
                    $count = Order::where('status_id', $id)->count();
                @endphp
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <div class="{{ $stat['color'] }} w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg mx-auto mb-2">
                        {{ $count }}
                    </div>
                    <div class="text-sm font-medium text-gray-700">{{ $stat['name'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>