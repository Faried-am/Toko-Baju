<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-800">Buat akun baru</h2>
    <p class="text-sm text-gray-500 mt-1 mb-6">Daftar gratis dan mulai belanja.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            @error('password_confirmation')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
             class="w-full bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition"
                style="padding: 14px 16px; font-size: 16px;">
                        Daftar
        </button>

        <p class="text-sm text-center text-gray-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold">Masuk</a>
        </p>
    </form>
</x-guest-layout>