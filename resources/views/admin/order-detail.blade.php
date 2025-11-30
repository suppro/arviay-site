<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Детали заказа #{{ $order->id }} — Вжух! Пицца</title>
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
                    <a href="{{ route('admin.orders') }}" class="hover:text-blue-200 font-medium">Заказы</a>
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
        <!-- Хлебные крошки -->
        <div class="mb-6">
            <a href="{{ route('admin.orders') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Назад к списку заказов
            </a>
        </div>

        <!-- Заголовок -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-4xl font-bold">Заказ #{{ $order->id }}</h1>
                <p class="text-gray-600 mt-2">Создан: {{ date('d.m.Y H:i', strtotime($order->created_at)) }}</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 rounded-full text-lg font-medium
                    @if($order->status_id == 1) bg-yellow-100 text-yellow-800
                    @elseif($order->status_id == 5) bg-green-100 text-green-800
                    @elseif($order->status_id == 6) bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800 @endif">
                    {{ $order->status->name }}
                </span>
                <div class="text-2xl font-bold mt-2">{{ number_format($order->total_price, 0, ',', ' ') }} ₽</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Информация о клиенте -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Информация о клиенте</h2>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Имя:</span>
                        <span class="ml-2">{{ $order->user->name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Телефон:</span>
                        <span class="ml-2">{{ $order->user->phone }}</span>
                    </div>
                    @if($order->user->email)
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <span class="ml-2">{{ $order->user->email }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="font-medium text-gray-700">Адрес доставки:</span>
                        <p class="ml-2 mt-1">{{ $order->delivery_address }}</p>
                    </div>
                    @if($order->comment)
                    <div>
                        <span class="font-medium text-gray-700">Комментарий:</span>
                        <p class="ml-2 mt-1 bg-gray-50 p-3 rounded-lg">{{ $order->comment }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Состав заказа -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Состав заказа</h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b pb-4">
                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $item->variant->product->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $item->variant->size_name }}</p>
                            <p class="text-sm text-gray-500">Количество: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">{{ number_format($item->price_at_moment * $item->quantity, 0, ',', ' ') }} ₽</p>
                            <p class="text-sm text-gray-500">{{ $item->price_at_moment }} ₽ × {{ $item->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between text-xl font-bold">
                            <span>Итого:</span>
                            <span>{{ number_format($order->total_price, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Смена статуса -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
            <h2 class="text-2xl font-bold mb-4">Смена статуса заказа</h2>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center gap-4">
                @csrf
                <select name="status_id" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(\App\Models\Status::all() as $status)
                        <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                    Обновить статус
                </button>
            </form>
        </div>
    </div>
</body>
</html>