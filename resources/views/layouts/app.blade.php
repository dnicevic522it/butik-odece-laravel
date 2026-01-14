<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Butik Odeće')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <!-- Navigacija -->
<nav style="background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 15px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center;">
                <a href="{{ route('home') }}" style="font-size: 24px; font-weight: bold; color: #1f2937; text-decoration: none;">
                    Butik Odeće
                </a>
                <span style="margin: 0 20px; color: #d1d5db;">|</span>
                <a href="{{ route('products.index') }}" style="color: #4b5563; text-decoration: none; margin-right: 30px;">
                    Proizvodi
                </a>
            </div>
            <div style="display: flex; align-items: center;">
                @auth
                    <a href="{{ route('cart') }}" style="color: #4b5563; text-decoration: none; margin-right: 30px;">
                        🛒 Korpa
                    </a>
                    <a href="{{ route('orders.index') }}" style="color: #4b5563; text-decoration: none; margin-right: 30px;">
                        Moje narudžbine
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" style="color: #2563eb; text-decoration: none; margin-right: 30px;">
                            Admin Panel
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="color: #4b5563; background: none; border: none; cursor: pointer;">
                            Odjavi se
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="color: #4b5563; text-decoration: none; margin-right: 20px;">
                        Prijavi se
                    </a>
                    <a href="{{ route('register') }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none;">
                        Registruj se
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

    <!-- Flash poruke -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Glavni sadržaj -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Butik Odeće. Sva prava zadržana.</p>
        </div>
    </footer>
</body>
</html>
