<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Guardian — ElderWatch</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        .bg-blue-600 { background-color: #00c4c4 !important; }
        .text-blue-700, .text-blue-600 { color: #00c4c4 !important; }
        .bg-blue-50 { background-color: #f0fdfd !important; }
        .ring-blue-500, .focus\:ring-blue-500:focus { --tw-ring-color: #00c4c4; }
        .hover\:bg-blue-700:hover { background-color: #00a8a8 !important; }
        .border-blue-600 { border-color: #00c4c4 !important; }
    </style>
</head>

<body class="bg-gray-50 antialiased" style="font-family: 'Instrument Sans', sans-serif;">

    @php
        $admin = session('admin');
        $firstName = $admin->firstName ?? 'Admin';
        $lastName  = $admin->lastName  ?? '';
        $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));

        $navItems = [
            ['label' => 'Dashboard',    'route' => 'dashboard',        'active' => false, 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label' => 'Reports',      'route' => 'reports.index',    'active' => false, 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Caregivers',   'route' => 'caregivers.index', 'active' => false, 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['label' => 'Guardians',    'route' => 'guardians.index',  'active' => true,  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Patients',     'route' => 'patients.index',   'active' => false, 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['label' => 'CCTV Devices', 'route' => 'cctv.index',       'active' => false, 'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.869v6.263a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z'],
            ['label' => 'Alerts',       'route' => 'alerts.index',     'active' => false, 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        ];

        $gFirst   = $guardian['firstName']  ?? '';
        $gMiddle  = $guardian['middleName'] ?? '';
        $gLast    = $guardian['lastName']   ?? '';
        $gDisplay = trim("$gFirst $gMiddle $gLast");
        $gInitial = strtoupper(substr($gFirst, 0, 1)) ?: 'G';
        $gDate    = $guardian['registeredDate'] ?? null;
    @endphp

    <div class="flex h-screen overflow-hidden">

        {{-- ===================== SIDEBAR ===================== --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 w-60 bg-white border-r border-gray-100 flex flex-col
               transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
            <div class="h-16 flex items-center gap-3 px-5 border-b border-gray-100 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="font-semibold text-gray-900 tracking-tight">ElderWatch</span>
            </div>
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                @foreach ($navItems as $item)
                    @php $exists = Route::has($item['route']); $href = $exists ? route($item['route']) : '#'; @endphp
                    <a href="{{ $href }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                           {{ $item['active'] ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $item['active'] ? 'text-blue-600' : 'text-gray-400' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="shrink-0 border-t border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center shrink-0">
                        <span class="text-xs font-semibold text-white">{{ $initials }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $firstName }} {{ $lastName }}</p>
                        <p class="text-xs text-gray-400 truncate">Administrator</p>
                    </div>
                    <a href="{{ route('logout') }}" title="Sign out" class="text-gray-400 hover:text-red-500 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/20 backdrop-blur-sm hidden lg:hidden"
            onclick="toggleSidebar()"></div>

        {{-- ===================== MAIN AREA ===================== --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60 overflow-hidden" style="margin-left:200px;">

            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0 z-20">
                <div class="flex items-center gap-3">
                    <a href="{{ route('guardians.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-sm font-semibold text-gray-900">Edit Guardian</h1>
                        <p class="text-xs text-gray-400">Update guardian information</p>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                    <span class="text-xs font-semibold text-white">{{ $initials }}</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-2xl mx-auto space-y-5">

                    {{-- Profile header --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center shrink-0">
                            <span class="text-xl font-bold text-purple-600">{{ $gInitial }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-base truncate">{{ $gDisplay ?: '—' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Guardian
                                @if ($gDate)
                                    · Registered {{ \Carbon\Carbon::parse($gDate)->format('M d, Y') }}
                                @endif
                            </p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-600">
                            userType: 3
                        </span>
                    </div>

                    <form action="{{ route('guardians.update', $userID) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
                                <ul class="text-sm text-red-600 space-y-0.5 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Personal Information --}}
                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100">
                                <h2 class="text-sm font-semibold text-gray-900">Personal Information</h2>
                            </div>
                            <div class="px-6 py-5 space-y-4">

                                {{-- Name row --}}
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="firstName"
                                            value="{{ old('firstName', $guardian['firstName'] ?? '') }}"
                                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl
                                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                   @error('firstName') border-red-400 @enderror" />
                                        @error('firstName')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Middle Name</label>
                                        <input type="text" name="middleName"
                                            value="{{ old('middleName', $guardian['middleName'] ?? '') }}"
                                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl
                                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                                            Last Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="lastName"
                                            value="{{ old('lastName', $guardian['lastName'] ?? '') }}"
                                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl
                                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                   @error('lastName') border-red-400 @enderror" />
                                        @error('lastName')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email"
                                        value="{{ old('email', $guardian['email'] ?? '') }}"
                                        class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                               @error('email') border-red-400 @enderror" />
                                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>

                                {{-- Phone + Address --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Phone Number</label>
                                        <input type="text" name="phoneNumber"
                                            value="{{ old('phoneNumber', $guardian['phoneNumber'] ?? '') }}"
                                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl
                                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Address</label>
                                        <input type="text" name="address"
                                            value="{{ old('address', $guardian['address'] ?? '') }}"
                                            class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-xl
                                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Reset Password (collapsible) --}}
                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                            <button type="button" onclick="togglePasswordSection()"
                                class="w-full flex items-center justify-between px-6 py-4 text-sm font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Reset Password
                                </span>
                                <svg id="pwChevron" class="w-4 h-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="passwordSection" class="hidden px-6 pb-5 pt-1 border-t border-gray-100 space-y-4">
                                <p class="text-xs text-gray-400">Leave blank to keep the current password.</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">New Password</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="newPass"
                                                placeholder="Min. 6 characters"
                                                class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-200 rounded-xl
                                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                                       @error('password') border-red-400 @enderror" />
                                            <button type="button" onclick="togglePass('newPass','eye1')"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                <svg id="eye1" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Confirm Password</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="newPassConf"
                                                placeholder="Repeat password"
                                                class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-200 rounded-xl
                                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                            <button type="button" onclick="togglePass('newPassConf','eye2')"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                <svg id="eye2" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('guardians.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200
                                       rounded-xl hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                                       text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const hidden  = sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full', !hidden);
            overlay.classList.toggle('hidden', !hidden);
        }

        function togglePasswordSection() {
            const section = document.getElementById('passwordSection');
            const chevron = document.getElementById('pwChevron');
            section.classList.toggle('hidden');
            chevron.style.transform = section.classList.contains('hidden') ? '' : 'rotate(180deg)';
        }

        function togglePass(fieldId, iconId) {
            const f = document.getElementById(fieldId);
            f.type  = f.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
