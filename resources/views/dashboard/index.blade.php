<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — ElderWatch</title>
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

        .text-blue-700, .text-blue-600 {
            color: #00c4c4 !important;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased" style="font-family: 'Instrument Sans', sans-serif;">

    @php
        $admin = session('admin');
        $firstName = $admin->firstName ?? 'Admin';
        $lastName = $admin->lastName ?? '';
        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));

        $navItems = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'active' => true,
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
                'active' => false,
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

            {{-- User card at bottom --}}
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
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60 overflow-hidden">

            {{-- Top header --}}
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0 z-20">

                {{-- Mobile menu button --}}
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Right actions --}}
                <div class="flex items-center gap-3">
                    {{-- Alert bell --}}
                    <button class="relative text-gray-400 hover:text-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($alertCount > 0)
                            <span
                                class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                                {{ $alertCount > 9 ? '9+' : $alertCount }}
                            </span>
                        @endif
                    </button>

                    {{-- Avatar --}}
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-xs font-semibold text-white">{{ $initials }}</span>
                    </div>
                </div>
            </header>

            {{-- ===================== CONTENT ===================== --}}
            <main class="flex-1 overflow-y-auto p-6 space-y-6" style="margin-left:200px;">

                {{-- Greeting banner --}}
                <div class="bg-blue-600 rounded-2xl px-6 py-5 flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Good
                            {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},</p>
                        <h2 class="text-white font-bold text-xl">{{ $firstName }} {{ $lastName }}</h2>
                        <p class="text-blue-200 text-xs mt-1">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="hidden sm:block opacity-20">
                        <svg class="w-16 h-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>

                {{-- ---- Stats row ---- --}}
                @php
                    $stats = [
                        [
                            'label' => 'Caregivers',
                            'value' => $caregiverCount,
                            'change' => 'Registered accounts',
                            'bg' => 'bg-purple-50',
                            'icon_bg' => 'bg-purple-100',
                            'icon_fg' => 'text-purple-600',
                            'val_fg' => 'text-purple-700',
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        ],
                        [
                            'label' => 'Guardians',
                            'value' => $guardianCount,
                            'change' => 'Registered accounts',
                            'bg' => 'bg-green-50',
                            'icon_bg' => 'bg-green-100',
                            'icon_fg' => 'text-green-600',
                            'val_fg' => 'text-green-700',
                            'icon' =>
                                'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                        ],
                        [
                            'label' => 'Patients',
                            'value' => $patientCount,
                            'change' => 'Under monitoring',
                            'bg' => 'bg-red-50',
                            'icon_bg' => 'bg-red-100',
                            'icon_fg' => 'text-red-500',
                            'val_fg' => 'text-red-600',
                            'icon' =>
                                'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                        ],
                        [
                            'label' => 'Active Alerts',
                            'value' => $alertCount,
                            'change' => 'Real-time incidents',
                            'bg' => 'bg-orange-50',
                            'icon_bg' => 'bg-orange-100',
                            'icon_fg' => 'text-orange-600',
                            'val_fg' => 'text-orange-600',
                            'icon' =>
                                'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($stats as $stat)
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-sm transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div
                                    class="w-10 h-10 rounded-xl {{ $stat['icon_bg'] }} flex items-center justify-center">
                                    <svg class="w-5 h-5 {{ $stat['icon_fg'] }}" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="{{ $stat['icon'] }}" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-2xl font-bold {{ $stat['val_fg'] }}">{{ $stat['value'] }}</p>
                            <p class="text-sm font-medium text-gray-700 mt-0.5">{{ $stat['label'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $stat['change'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- ---- Bottom two-column grid ---- --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Recent Incidents (2/3 width) --}}
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Recent Incident Reports</h3>
                            @if (Route::has('reports.index'))
                                <a href="{{ route('reports.index') }}"
                                    class="text-xs text-blue-600 font-medium hover:text-blue-700 transition-colors">
                                    View all →
                                </a>
                            @endif
                        </div>

                        @if (count($recentReports) > 0)
                            <div class="divide-y divide-gray-50">
                                @foreach ($recentReports as $report)
                                    <div class="flex items-start gap-4 px-6 py-4">
                                        {{-- Severity dot --}}
                                        <div class="mt-1 shrink-0">
                                            @php
                                                $severity = strtolower($report['severity'] ?? 'low');
                                                $dotColor = match ($severity) {
                                                    'high' => 'bg-red-500',
                                                    'medium' => 'bg-yellow-400',
                                                    default => 'bg-green-400',
                                                };
                                            @endphp
                                            <span class="block w-2 h-2 rounded-full {{ $dotColor }}"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $report['patientName'] ?? 'Unknown Patient' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5 truncate">
                                                {{ $report['description'] ?? 'Incident detected' }}
                                            </p>
                                        </div>
                                        <div class="shrink-0 text-right">
                                            @php
                                                $badgeClass = match ($severity) {
                                                    'high' => 'bg-red-50 text-red-600',
                                                    'medium' => 'bg-yellow-50 text-yellow-700',
                                                    default => 'bg-green-50 text-green-700',
                                                };
                                            @endphp
                                            <span
                                                class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $badgeClass }}">
                                                {{ ucfirst($severity) }}
                                            </span>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $report['date'] ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-400">No incident reports yet</p>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Actions (1/3 width) --}}
                    <div class="bg-white rounded-2xl border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-50">
                            <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-4 space-y-2">

                            @php
                                $actions = [
                                    [
                                        'label' => 'Add Caregiver',
                                        'route' => 'caregivers.create',
                                        'bg' => 'bg-purple-50',
                                        'fg' => 'text-purple-700',
                                        'icon' =>
                                            'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                                    ],
                                    [
                                        'label' => 'Add Guardian',
                                        'route' => 'guardians.create',
                                        'bg' => 'bg-green-50',
                                        'fg' => 'text-green-700',
                                        'icon' =>
                                            'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                                    ],
                                    [
                                        'label' => 'Add Patient',
                                        'route' => 'patients.create',
                                        'bg' => 'bg-red-50',
                                        'fg' => 'text-red-600',
                                        'icon' => 'M12 4v16m8-8H4',
                                    ],
                                    [
                                        'label' => 'Register CCTV',
                                        'route' => 'cctv.create',
                                        'bg' => 'bg-yellow-50',
                                        'fg' => 'text-yellow-700',
                                        'icon' =>
                                            'M15 10l4.553-2.069A1 1 0 0121 8.869v6.263a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z',
                                    ],
                                    [
                                        'label' => 'View All Reports',
                                        'route' => 'reports.index',
                                        'bg' => 'bg-blue-50',
                                        'fg' => 'text-blue-700',
                                        'icon' =>
                                            'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                    ],
                                    [
                                        'label' => 'View Alerts',
                                        'route' => 'alerts.index',
                                        'bg' => 'bg-orange-50',
                                        'fg' => 'text-orange-700',
                                        'icon' =>
                                            'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                                    ],
                                ];
                            @endphp

                            @foreach ($actions as $action)
                                @php $href = Route::has($action['route']) ? route($action['route']) : '#'; @endphp
                                <a href="{{ $href }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $action['bg'] }} hover:opacity-80 transition-opacity">
                                    <div
                                        class="w-7 h-7 rounded-lg bg-white/60 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 {{ $action['fg'] }}" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="{{ $action['icon'] }}" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-sm font-medium {{ $action['fg'] }}">{{ $action['label'] }}</span>
                                    <svg class="w-3.5 h-3.5 {{ $action['fg'] }} ml-auto opacity-50" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ---- Module cards row ---- --}}
                @php
                    $modules = [
                        [
                            'title' => 'Caregiver Management',
                            'desc' => 'Register accounts, assign patients, review activity & incident reports.',
                            'count' => $caregiverCount,
                            'unit' => 'caregivers',
                            'route' => 'caregivers.index',
                            'bg' => 'bg-purple-50',
                            'fg' => 'text-purple-700',
                            'badge' => 'bg-purple-100 text-purple-700',
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        ],
                        [
                            'title' => 'Guardian Management',
                            'desc' => 'Manage guardian profiles and their access to monitoring reports.',
                            'count' => $guardianCount,
                            'unit' => 'guardians',
                            'route' => 'guardians.index',
                            'bg' => 'bg-green-50',
                            'fg' => 'text-green-700',
                            'badge' => 'bg-green-100 text-green-700',
                            'icon' =>
                                'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                        ],
                        [
                            'title' => 'Patient Info',
                            'desc' => 'Maintain patient records linked to the YOLO body posture detection system.',
                            'count' => $patientCount,
                            'unit' => 'patients',
                            'route' => 'patients.index',
                            'bg' => 'bg-red-50',
                            'fg' => 'text-red-600',
                            'badge' => 'bg-red-100 text-red-600',
                            'icon' =>
                                'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                        ],
                        [
                            'title' => 'CCTV Device Register',
                            'desc' => 'Register and manage CCTV cameras used for real-time surveillance.',
                            'count' => $cctvCount,
                            'unit' => 'devices',
                            'route' => 'cctv.index',
                            'bg' => 'bg-yellow-50',
                            'fg' => 'text-yellow-700',
                            'badge' => 'bg-yellow-100 text-yellow-700',
                            'icon' =>
                                'M15 10l4.553-2.069A1 1 0 0121 8.869v6.263a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z',
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($modules as $mod)
                        @php $href = Route::has($mod['route']) ? route($mod['route']) : '#'; @endphp
                        <a href="{{ $href }}"
                            class="group bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-all">
                            <div class="flex items-start justify-between mb-4">
                                <div
                                    class="w-10 h-10 rounded-xl {{ $mod['bg'] }} flex items-center justify-center">
                                    <svg class="w-5 h-5 {{ $mod['fg'] }}" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="{{ $mod['icon'] }}" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $mod['badge'] }}">
                                    {{ $mod['count'] }}
                                </span>
                            </div>
                            <h3
                                class="text-sm font-semibold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">
                                {{ $mod['title'] }}
                            </h3>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $mod['desc'] }}</p>
                            <div class="mt-4 flex items-center gap-1 text-xs font-medium {{ $mod['fg'] }}">
                                Manage {{ $mod['unit'] }}
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>

            </main>{{-- /main --}}
        </div>{{-- /main area --}}
    </div>{{-- /flex wrapper --}}

    <script>
        (function () {
            var sb = document.getElementById('sidebar'),
                ov = document.getElementById('sidebar-overlay');
            if (!sb || !ov) return;
            function applyState(open) {
                sb.style.transform = open ? 'translateX(0)'    : 'translateX(-100%)';
                sb.style.translate  = open ? '0 0'              : '-100% 0';
                ov.style.display   = open ? 'block'            : 'none';
                sb.setAttribute('data-open', open ? '1' : '0');
            }
            if (window.innerWidth < 1024) applyState(false);
            window.toggleSidebar = function () {
                applyState(sb.getAttribute('data-open') !== '1');
            };
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    sb.style.transform = ''; sb.style.translate = ''; ov.style.display = '';
                } else if (sb.getAttribute('data-open') !== '1') {
                    applyState(false);
                }
            });
        }());
    </script>

    @if (session()->pull('successLogin'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Welcome back!',
                    showConfirmButton: false,
                    timer: 800
                });
            }, 500);
        </script>
        {{ session()->forget('successLogin') }}
    @endif

</body>

</html>
