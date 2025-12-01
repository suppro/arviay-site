<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход — АО «Арвиай»</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <!-- Логотип -->
        <div class="text-center mb-10">
            <div class="text-6xl mb-4">✈️</div>
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">АО «Арвиай»</h1>
            <h2 class="text-3xl font-bold text-gray-900 mt-6">Вход в аккаунт</h2>
            <p class="text-gray-600 mt-3 font-medium">Войдите для оформления заказов</p>
        </div>

        <!-- Форма -->
        <div class="bg-white py-10 px-8 shadow-2xl rounded-3xl border border-gray-100">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Логин -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email *</label>
                        <input id="email" name="email" type="email" required 
                               class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                               value="{{ old('email') }}" placeholder="Введите ваш email">
                        @error('email')
                            <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Пароль -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Пароль *</label>
                        <input id="password" name="password" type="password" required 
                               class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium"
                               placeholder="Введите ваш пароль">
                        @error('password')
                            <p class="text-sm text-red-600 mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Запомнить меня -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember" class="ml-3 block text-sm text-gray-700 font-medium cursor-pointer">
                            Запомнить меня
                        </label>
                    </div>
                </div>

                <!-- Кнопка входа -->
                <button type="submit" 
                        class="w-full mt-8 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-300 font-bold text-lg shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    Войти →
                </button>

                <!-- Ссылка на регистрацию -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Нет аккаунта? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-bold transition-colors">Зарегистрируйтесь</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>