<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — ElderWatch</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Top bar --}}
    <div class="px-6 py-5">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <span class="font-semibold text-sm">ElderWatch</span>
        </a>
    </div>

    {{-- Card --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Admin Sign In</h1>
                <p class="text-sm text-gray-500">Access the ElderWatch management portal</p>
            </div>

            {{-- Card box --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

                {{-- Error alert --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-100 rounded-xl px-4 py-3 flex items-start gap-3">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Session error (e.g. invalid credentials) --}}
                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-100 rounded-xl px-4 py-3 flex items-start gap-3">
                        <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email address
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            placeholder="admin@example.com"
                            class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                   @error('email') border-red-300 bg-red-50 @else border-gray-200 bg-white @enderror"
                        >
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 pr-11 rounded-xl border border-gray-200 bg-white text-sm text-gray-900
                                       placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                                       @error('password') border-red-300 bg-red-50 @enderror"
                            >
                            {{-- Toggle visibility --}}
                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                tabindex="-1"
                            >
                                <svg id="eye-icon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-xl hover:bg-blue-700 active:bg-blue-800
                               transition-colors text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Sign in
                    </button>
                </form>
            </div>

            {{-- Footer note --}}
            <p class="text-center text-xs text-gray-400 mt-6">
                ElderWatch Admin &mdash; Authorised access only
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eye = document.getElementById('eye-icon');
            const eyeOff = document.getElementById('eye-off-icon');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
