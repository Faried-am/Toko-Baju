<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-800">Selamat datang kembali</h2>
    <p class="text-sm text-gray-500 mt-1 mb-6">Masuk untuk melanjutkan belanja.</p>

    @if (session('status'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition"
            style="padding: 14px 16px; font-size: 16px;">
             Masuk
        </button>

        <p class="text-sm text-center text-gray-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 font-semibold">Daftar</a>
        </p>
    </form>
</x-guest-layout>