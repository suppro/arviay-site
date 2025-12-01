<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Оформление заказа — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased">
    <!-- Шапка -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-xl sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
        <div class="container mx-auto px-4 py-5 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-3xl font-extrabold tracking-tight hover:scale-105 transition-transform duration-200">
                ✈️ АО «Арвиай»
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <span class="hidden md:block text-blue-100 font-semibold">Привет, {{ auth()->user()->name }}! 👋</span>
                    <a href="{{ route('cart') }}" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                        🛒 Корзина
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold hover:bg-blue-50 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">Выйти</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Оформление заказа</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Форма заказа -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-2xl">
                        📍
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Данные для доставки</h2>
                </div>
                
                <form action="{{ route('order.create') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="customer_name" class="block text-sm font-bold text-gray-700 mb-2">Имя *</label>
                            <input type="text" id="customer_name" name="customer_name" required
                                   class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                                   value="{{ auth()->user()->name ?? old('customer_name') }}"
                                   placeholder="Ваше имя">
                            @error('customer_name')
                                <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-bold text-gray-700 mb-2">Телефон *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" required
                                   class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                                   value="{{ auth()->user()->phone ?? old('customer_phone') }}"
                                   placeholder="+79991234567">
                            @error('customer_phone')
                                <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="delivery_address" class="block text-sm font-bold text-gray-700 mb-2">Адрес доставки *</label>
                            <input type="text" id="delivery_address" name="delivery_address" required
                                   class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                                   value="{{ old('delivery_address') }}"
                                   placeholder="Укажите адрес доставки">
                            @error('delivery_address')
                                <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-bold text-gray-700 mb-2">Способ оплаты *</label>
                            <select id="payment_method" name="payment_method" required
                                    class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium">
                                <option value="">Выберите способ оплаты</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Наличными при получении</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Банковской картой</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Банковский перевод</option>
                            </select>
                            @error('payment_method')
                                <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="delivery_method" class="block text-sm font-bold text-gray-700 mb-2">Способ доставки *</label>
                            <select id="delivery_method" name="delivery_method" required
                                    class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium">
                                <option value="">Выберите способ доставки</option>
                                <option value="pickup" {{ old('delivery_method') == 'pickup' ? 'selected' : '' }}>Самовывоз</option>
                                <option value="courier" {{ old('delivery_method') == 'courier' ? 'selected' : '' }}>Курьерская доставка</option>
                                <option value="transport" {{ old('delivery_method') == 'transport' ? 'selected' : '' }}>Транспортная компания</option>
                            </select>
                            @error('delivery_method')
                                <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-bold text-gray-700 mb-2">Комментарий к заказу</label>
                            <textarea id="comment" name="comment" rows="4"
                                      class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium resize-none"
                                      placeholder="Дополнительные пожелания...">{{ old('comment') }}</textarea>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full mt-8 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-300 font-bold text-lg shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                        ✅ Подтвердить заказ
                    </button>
                </form>
            </div>

            <!-- Информация о заказе -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white text-2xl">
                        🛒
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Ваш заказ</h2>
                </div>
                
                <div class="space-y-6">
                    @foreach($cart as $item)
                    <div class="flex justify-between items-start border-b-2 border-gray-100 pb-6 last:border-0">
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $item['product']->name }}</h3>
                            @if($item['product']->sku)
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="px-2 py-1 bg-gray-100 rounded-lg font-semibold">Артикул: {{ $item['product']->sku }}</span>
                                </p>
                            @endif
                            <p class="text-sm text-gray-600 font-medium">Количество: <span class="font-bold text-blue-600">{{ $item['quantity'] }}</span></p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="font-extrabold text-xl text-gray-900">{{ number_format($item['product']->price * $item['quantity'], 0, ',', ' ') }} ₽</p>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="border-t-2 border-gray-200 pt-6 mt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-gray-700">Итого:</span>
                            <span class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
