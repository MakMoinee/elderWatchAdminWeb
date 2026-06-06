<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Caregiver — ElderWatch</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .bg-blue-600 {
            background-color: #00c4c4 !important;
        }

        .text-blue-700,
        .text-blue-600 {
            color: #00c4c4 !important;
        }

        .bg-blue-50 {
            background-color: #f0fdfd !important;
        }

        .ring-blue-500,
        .focus\:ring-blue-500:focus {
            --tw-ring-color: #00c4c4;
        }

        .hover\:bg-blue-700:hover {
            background-color: #00a8a8 !important;
        }

        .border-blue-600 {
            border-color: #00c4c4 !important;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased" style="font-family: 'Instrument Sans', sans-serif;">

    @php
        $admin = session('admin');
        $firstName = $admin->firstName ?? 'Admin';
        $lastName = $admin->lastName ?? '';
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));

        // $userID is passed from the controller — it is the Firestore docID
        // (the route key). Do NOT overwrite it here.
        $cgFirst = $caregiver['firstName'] ?? '';
        $cgMiddle = $caregiver['middleName'] ?? '';
        $cgLast = $caregiver['lastName'] ?? '';
        $cgEmail = $caregiver['email'] ?? '';
        $cgPhone = $caregiver['phoneNumber'] ?? '';
        $cgAddress = $caregiver['address'] ?? '';
        $cgFullName = trim("$cgFirst $cgLast");

        $navItems = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'active' => false,
                'icon' =>
                    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            ],
            [
                'label' => 'Reports',
                'route' => 'reports.index',
                'active' => false,
                'icon' =>
                    'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            ],
            [
                'label' => 'Caregivers',
                'route' => 'caregivers.index',
                'active' => true,
                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            ],
            [
                'label' => 'Guardians',
                'route' => 'guardians.index',
                'active' => false,
                'icon' =>
                    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            ],
            [
                'label' => 'Patients',
                'route' => 'patients.index',
                'active' => false,
                'icon' =>
                    'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
            ],
            [
                'label' => 'Activities',
                'route' => 'activities.index',
                'active' => false,
                'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            ],
            [
                'label' => 'CCTV Devices',
                'route' => 'cctv.index',
                'active' => false,
                'icon' =>
                    'M15 10l4.553-2.069A1 1 0 0121 8.869v6.263a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z',
            ],
            [
                'label' => 'Alerts',
                'route' => 'alerts.index',
                'active' => false,
                'icon' =>
                    'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            ],
        ];
    @endphp

    <div class="flex h-screen overflow-hidden">

        {{-- ===================== SIDEBAR ===================== --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 w-60 bg-white border-r border-gray-100 flex flex-col
               transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">

            {{-- Logo --}}
            <div class="h-16 flex items-center gap-3 px-5 border-b border-gray-100 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="font-semibold text-gray-900 tracking-tight">ElderWatch</span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                @foreach ($navItems as $item)
                    @php
                        $exists = Route::has($item['route']);
                        $href = $exists ? route($item['route']) : '#';
                    @endphp
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

            {{-- User card --}}
            <div class="shrink-0 border-t border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center shrink-0">
                        <span class="text-xs font-semibold text-white">{{ $initials }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $firstName }} {{ $lastName }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">Administrator</p>
                    </div>
                    <a href="{{ route('logout') }}" title="Sign out"
                        class="text-gray-400 hover:text-red-500 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        {{-- Sidebar overlay (mobile) --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/20 backdrop-blur-sm hidden lg:hidden"
            onclick="toggleSidebar()"></div>

        {{-- ===================== MAIN AREA ===================== --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60 overflow-hidden" style="margin-left:200px;"
            id="mainAreaDiv">

            {{-- Top header --}}
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0 z-20">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="hidden lg:block">
                    <h1 class="text-sm font-semibold text-gray-900">Caregiver Management</h1>
                    <p class="text-xs text-gray-400">Manage all registered caregiver accounts</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-xs font-semibold text-white">{{ $initials }}</span>
                    </div>
                </div>
            </header>

            {{-- ===================== CONTENT ===================== --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
                    <a href="{{ route('caregivers.index') }}"
                        class="hover:text-gray-700 transition-colors">Caregivers</a>
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600 font-medium">Edit</span>
                </nav>

                <div class="max-w-2xl">

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-100 rounded-xl px-4 py-3 flex items-start gap-3">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <ul class="text-sm text-red-700 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form card --}}
                    <form method="POST" action="{{ route('caregivers.update', ['caregiver' => $userID]) }}">
                        @csrf
                        @method('PUT')

                        {{-- Profile summary header --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center shrink-0">
                                    <span class="text-xl font-bold text-purple-600">
                                        {{ strtoupper(substr($cgFirst, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-base">{{ $cgFullName ?: 'Caregiver' }}
                                    </p>
                                    <p class="text-sm text-gray-400">{{ $cgEmail }}</p>
                                    <span
                                        class="inline-block mt-1 text-xs font-medium bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full">
                                        Caregiver
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Personal Information --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Personal Information
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                {{-- First Name --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="firstName" value="{{ old('firstName', $cgFirst) }}"
                                        placeholder="e.g. Maria"
                                        class="w-full px-3 py-2.5 text-sm border rounded-xl bg-gray-50
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition
                                           @error('firstName') border-red-300 @else border-gray-200 @enderror">
                                    @error('firstName')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Middle Name --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Middle Name</label>
                                    <input type="text" name="middleName"
                                        value="{{ old('middleName', $cgMiddle) }}" placeholder="e.g. Santos"
                                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                                </div>

                                {{-- Last Name --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="lastName" value="{{ old('lastName', $cgLast) }}"
                                        placeholder="e.g. Reyes"
                                        class="w-full px-3 py-2.5 text-sm border rounded-xl bg-gray-50
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition
                                           @error('lastName') border-red-300 @else border-gray-200 @enderror">
                                    @error('lastName')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Contact Information --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                            <h3 class="text-sm font-semibold text-gray-900 mb-5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Contact Information
                            </h3>

                            <div class="space-y-4">
                                {{-- Email --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                        <input type="email" name="email" value="{{ old('email', $cgEmail) }}"
                                            placeholder="caregiver@example.com"
                                            class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-xl bg-gray-50
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition
                                               @error('email') border-red-300 @else border-gray-200 @enderror">
                                    </div>
                                    @error('email')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Phone Number</label>
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <input type="text" name="phoneNumber"
                                            value="{{ old('phoneNumber', $cgPhone) }}" placeholder="+63 912 345 6789"
                                            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Address</label>
                                    <div class="relative">
                                        <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <textarea name="address" rows="2" placeholder="Street, Barangay, City, Province"
                                            class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition resize-none">{{ old('address', $cgAddress) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Reset Password (optional) --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
                            <button type="button" onclick="togglePasswordSection()"
                                class="w-full flex items-center justify-between text-sm font-semibold text-gray-900">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Reset Password
                                    <span class="text-xs font-normal text-gray-400">(optional)</span>
                                </span>
                                <svg id="pw-chevron" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="passwordSection" class="hidden mt-5 space-y-4">
                                <p class="text-xs text-gray-400">Leave blank to keep the current password.</p>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">New Password</label>
                                    <div class="relative">
                                        <input id="newPassword" type="password" name="password"
                                            placeholder="At least 6 characters"
                                            class="w-full px-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition
                                               @error('password') border-red-300 @enderror">
                                        <button type="button" onclick="togglePw('newPassword', 'eye1', 'eyeOff1')"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="eye1" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="eyeOff1" class="w-4 h-4 hidden" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Confirm New
                                        Password</label>
                                    <div class="relative">
                                        <input id="confirmPassword" type="password" name="password_confirmation"
                                            placeholder="Re-enter new password"
                                            class="w-full px-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                                        <button type="button"
                                            onclick="togglePw('confirmPassword', 'eye2', 'eyeOff2')"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <svg id="eye2" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg id="eyeOff2" class="w-4 h-4 hidden" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('caregivers.index') }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-600 border border-gray-200
                                   rounded-xl hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold
                                   px-5 py-2.5 rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
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

    {{-- Hidden delete form --}}
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        (function() {
            var sb = document.getElementById('sidebar'),
                ov = document.getElementById('sidebar-overlay');
            if (!sb || !ov) return;

            function applyState(open) {
                sb.style.transform = open ? 'translateX(0)' : 'translateX(-100%)';
                sb.style.translate = open ? '0 0' : '-100% 0';
                ov.style.display = open ? 'block' : 'none';
                sb.setAttribute('data-open', open ? '1' : '0');
            }
            if (window.innerWidth < 1024) applyState(false);
            window.toggleSidebar = function() {
                applyState(sb.getAttribute('data-open') !== '1');
                document.getElementById('mainAreaDiv').setAttribute('style', 'margin-left:' + (sb.getAttribute(
                    'data-open') === '1' ? '200px' : '0') + '; transition: margin-left 0.2s ease-in-out;');
            };
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    sb.style.transform = '';
                    sb.style.translate = '';
                    ov.style.display = '';
                } else if (sb.getAttribute('data-open') !== '1') {
                    applyState(false);
                }
            });
        }());

        function togglePasswordSection() {
            const section = document.getElementById('passwordSection');
            const chevron = document.getElementById('pw-chevron');
            const isHidden = section.classList.contains('hidden');
            section.classList.toggle('hidden', !isHidden);
            chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
        }

        function togglePw(inputId, eyeId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);
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

    @if (session()->pull('success'))
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1800,
                toast: true,
            });
        </script>
    @endif

</body>

</html>
