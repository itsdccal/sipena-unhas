<x-guest-layout>
    <!-- Header with UNHAS Logo -->
    <div class="mb-6 flex flex-col items-center">
        <img src="{{ asset('images/unhas.png') }}" alt="Logo UNHAS" class="h-20 mb-3 drop-shadow-xl">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Selamat Datang</h2>
        <p class="text-gray-600 text-sm mt-1 text-center">Silakan login untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- NIP -->
        <div>
            <x-input-label for="nip" :value="__('NIP')" class="text-gray-700 font-semibold" />
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input id="nip" type="text" name="nip" :value="old('nip')" required autofocus
                    autocomplete="username"
                    class="pl-10 bg-white/60 backdrop-blur-md border-white/40 focus:bg-white/80 focus:border-blue-400 focus:ring-blue-400/50 transition-all duration-200"
                    placeholder="Masukkan NIP" />
            </div>
            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                    class="pl-10 bg-white/60 backdrop-blur-md border-white/40 focus:bg-white/80 focus:border-blue-400 focus:ring-blue-400/50 transition-all duration-200"
                    placeholder="Masukkan password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-6">
            <button type="submit"
                class="w-full justify-center inline-flex items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl">
                Masuk
            </button>
        </div>
    </form>
</x-guest-layout>
