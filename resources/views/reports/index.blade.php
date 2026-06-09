<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reports — ElderWatch</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        .bg-blue-600            { background-color: #00c4c4 !important; }
        .text-blue-700,
        .text-blue-600          { color: #00c4c4 !important; }
        .bg-blue-50             { background-color: #f0fdfd !important; }
        .ring-blue-500,
        .focus\:ring-blue-500:focus { --tw-ring-color: #00c4c4; }
        .hover\:bg-blue-700:hover   { background-color: #00a8a8 !important; }
        .border-blue-600        { border-color: #00c4c4 !important; }

        .preset-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #6b7280;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .preset-pill:hover { background: #f9fafb; color: #111827; }
        .preset-pill.active {
            background: #00c4c4;
            border-color: #00c4c4;
            color: #fff;
        }
        input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; opacity: 0.6; }

        /* ── Print / PDF styles ───────────────────────────────────────── */
        .print-only { display: none; }

        @media print {
            @page {
                size: A4 portrait;
                margin: 16mm 14mm;
            }

            /* Hide everything we don't want */
            #sidebar,
            #sidebar-overlay,
            .no-print { display: none !important; }

            /* Kill the fixed header */
            header { display: none !important; }

            /* Full-width main area */
            #mainAreaDiv {
                margin-left: 0 !important;
                display: block !important;
                overflow: visible !important;
                height: auto !important;
            }

            main {
                overflow: visible !important;
                padding: 0 !important;
            }

            body, html { background: #fff !important; }

            /* Cards — remove shadows, keep borders */
            .bg-white {
                box-shadow: none !important;
                break-inside: avoid;
            }

            /* Print header visible */
            .print-only { display: block !important; }

            /* Stat cards grid: always 5 columns */
            .grid { break-inside: avoid; }

            /* Force chart containers to a fixed height so canvas renders */
            canvas { max-width: 100% !important; }

            /* Trend + donut row — lay them side by side */
            .lg\:grid-cols-3 {
                grid-template-columns: 2fr 1fr !important;
                display: grid !important;
            }
            .lg\:col-span-2 { grid-column: span 2 !important; }

            /* Per-caregiver bars side by side */
            .lg\:grid-cols-2 {
                grid-template-columns: 1fr 1fr !important;
                display: grid !important;
            }

            /* Spacing */
            .space-y-5 > * + * { margin-top: 12px !important; }

            /* Page break before caregiver charts */
            .print-break { page-break-before: always; margin-top: 0 !important; }
        }
    </style>
</head>

<body class="bg-gray-50 antialiased" style="font-family: 'Instrument Sans', sans-serif;">

    @php
        $admin     = session('admin');
        $firstName = $admin->firstName ?? 'Admin';
        $lastName  = $admin->lastName  ?? '';
        $initials  = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));

        $navItems = [
            ['label' => 'Dashboard',    'route' => 'dashboard',        'active' => false,
             'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label' => 'Reports',      'route' => 'reports.index',    'active' => true,
             'icon'  => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Caregivers',   'route' => 'caregivers.index', 'active' => false,
             'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['label' => 'Guardians',    'route' => 'guardians.index',  'active' => false,
             'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Patients',     'route' => 'patients.index',   'active' => false,
             'icon'  => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['label' => 'Activities',   'route' => 'activities.index', 'active' => false,
             'icon'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label' => 'CCTV Devices', 'route' => 'cctv.index',       'active' => false,
             'icon'  => 'M15 10l4.553-2.069A1 1 0 0121 8.869v6.263a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z'],
            ['label' => 'Alerts',       'route' => 'alerts.index',     'active' => false,
             'icon'  => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        ];
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
                    @php $href = Route::has($item['route']) ? route($item['route']) : '#'; @endphp
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

        <div id="sidebar-overlay"
            class="fixed inset-0 z-30 bg-black/20 backdrop-blur-sm hidden lg:hidden"
            onclick="toggleSidebar()"></div>

        {{-- ===================== MAIN AREA ===================== --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60 overflow-hidden"
            style="margin-left:200px;" id="mainAreaDiv">

            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0 z-20">
                <button onclick="toggleSidebar()"
                    class="lg:hidden text-gray-500 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden lg:block">
                    <h1 class="text-sm font-semibold text-gray-900">Reports & Analytics</h1>
                    <p class="text-xs text-gray-400">Visual overview of system data and activity trends</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-xs font-semibold text-white">{{ $initials }}</span>
                    </div>
                </div>
            </header>

            {{-- ===================== CONTENT ===================== --}}
            <main class="flex-1 overflow-y-auto p-6 space-y-5">

                {{-- Page heading --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Analytics Overview</h2>
                        <p class="text-sm text-gray-400 mt-0.5">System-wide statistics and trends</p>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Active range badge --}}
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium
                                     bg-teal-50 text-teal-700 border border-teal-100 rounded-full px-3 py-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $filterLabel }}
                        </span>
                        {{-- Print / Export PDF button --}}
                        <button onclick="printReport()"
                            class="no-print"
                            style="display:inline-flex;align-items:center;gap:8px;background:#111827;color:#fff;
                                   font-size:0.875rem;font-weight:600;padding:0.5rem 1rem;border-radius:0.75rem;
                                   border:none;cursor:pointer;transition:background 0.15s;white-space:nowrap;"
                            onmouseover="this.style.background='#374151'"
                            onmouseout="this.style.background='#111827'">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Export PDF
                        </button>
                    </div>
                </div>

                {{-- Print-only report header (hidden on screen) --}}
                <div class="print-only" style="border-bottom: 2px solid #00c4c4; padding-bottom: 14px; margin-bottom: 4px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                                <div style="width:28px;height:28px;border-radius:8px;background:#00c4c4;display:flex;align-items:center;justify-content:center;">
                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                        <circle cx="12" cy="12" r="3"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                <span style="font-size:15px;font-weight:700;color:#111827;">ElderWatch — Analytics Report</span>
                            </div>
                            <p style="font-size:12px;color:#6b7280;margin:0;">Period: <strong style="color:#00c4c4;">{{ $filterLabel }}</strong></p>
                        </div>
                        <p style="font-size:11px;color:#9ca3af;text-align:right;">
                            Generated: {{ now()->format('M d, Y · g:i A') }}<br>
                            Administrator
                        </p>
                    </div>
                </div>

                {{-- ── DATE RANGE FILTER CARD ─────────────────────────────────── --}}
                <div class="no-print bg-white rounded-2xl border border-gray-100 p-4">
                    <form id="filterForm" method="GET" action="{{ route('reports.index') }}">
                        <input type="hidden" id="presetField" name="preset" value="{{ $preset }}">

                        <div class="flex flex-col gap-3">

                            {{-- Preset pills --}}
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $presets = [
                                        'this_week'      => 'This Week',
                                        'this_month'     => 'This Month',
                                        'last_3_months'  => 'Last 3 Months',
                                        'last_6_months'  => 'Last 6 Months',
                                        'this_year'      => 'This Year',
                                        'all'            => 'All Time',
                                        'custom'         => 'Custom',
                                    ];
                                @endphp
                                @foreach ($presets as $key => $label)
                                    <button type="button"
                                        onclick="applyPreset('{{ $key }}')"
                                        class="preset-pill {{ $preset === $key ? 'active' : '' }}"
                                        id="pill-{{ $key }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>

                            {{-- Date inputs + Apply --}}
                            <div class="flex flex-wrap items-end gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                                    <input type="date" id="startDate" name="start_date"
                                        value="{{ $filterStart }}"
                                        onchange="onDateChange()"
                                        class="px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50
                                               focus:outline-none focus:ring-2 focus:ring-teal-400 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                                    <input type="date" id="endDate" name="end_date"
                                        value="{{ $filterEnd }}"
                                        onchange="onDateChange()"
                                        class="px-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50
                                               focus:outline-none focus:ring-2 focus:ring-teal-400 transition">
                                </div>
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-sm font-semibold
                                           px-4 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                                    </svg>
                                    Apply
                                </button>
                                @if ($preset !== 'last_6_months')
                                    <a href="{{ route('reports.index') }}"
                                        class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500
                                               border border-gray-200 px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ── STAT CARDS ─────────────────────────────────────────────── --}}
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">

                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Patients</span>
                            <div class="w-8 h-8 rounded-xl bg-teal-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalPatients }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total registered</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Caregivers</span>
                            <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalCaregivers }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total active</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-5 relative">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Activities</span>
                            <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalActivities }}</p>
                        <p class="text-xs text-gray-400 mt-1">In selected range</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Devices</span>
                            <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10l4.553-2.069A1 1 0 0121 8.869v6.263a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalDevices }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total CCTV devices</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Alerts</span>
                            <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalAlerts }}</p>
                        <p class="text-xs text-gray-400 mt-1">In selected range</p>
                    </div>
                </div>

                {{-- ── ROW 2: TREND LINE + AGE DONUT ─────────────────────────── --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                    {{-- Trend chart --}}
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Monthly Trend</h3>
                                <p class="text-xs text-gray-400 mt-0.5">Activities vs Alerts — {{ $filterLabel }}</p>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block w-3 h-3 rounded-full bg-teal-400"></span> Activities
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-300"></span> Alerts
                                </span>
                            </div>
                        </div>
                        @if (array_sum($monthCounts) === 0 && array_sum($alertCounts) === 0)
                            <div class="flex flex-col items-center justify-center" style="height:230px;">
                                <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm text-gray-400">No data in this date range</p>
                            </div>
                        @else
                            <div class="relative" style="height:230px;">
                                <canvas id="trendChart"></canvas>
                            </div>
                        @endif
                    </div>

                    {{-- Age distribution --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-900">Patient Age Groups</h3>
                            <p class="text-xs text-gray-400 mt-0.5">All patients · not date-filtered</p>
                        </div>
                        <div class="relative flex items-center justify-center" style="height:180px;">
                            <canvas id="ageChart"></canvas>
                        </div>
                        @php
                            $agePalette = ['#2dd4bf','#818cf8','#fb923c','#34d399','#f472b6','#94a3b8'];
                            $ai = 0;
                        @endphp
                        <div class="mt-3 grid grid-cols-2 gap-1">
                            @foreach ($ageGroups as $label => $count)
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0"
                                        style="background:{{ $agePalette[$ai % count($agePalette)] }}"></span>
                                    {{ $label }}: <strong class="text-gray-700 ml-0.5">{{ $count }}</strong>
                                </div>
                                @php $ai++ @endphp
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── ROW 3: PER-CAREGIVER BARS ────────────────────────────── --}}
                <div class="print-break grid grid-cols-1 lg:grid-cols-2 gap-4">

                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-900">Activities per Caregiver</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Filtered range · top 8</p>
                        </div>
                        @if (count($actPerCaregiver) > 0)
                            <div class="relative"
                                style="height:{{ max(180, count($actPerCaregiver) * 40) }}px;">
                                <canvas id="actCaregiverChart"></canvas>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm text-gray-400">No activities in this date range</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-900">Patients per Caregiver</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Unique patients handled · filtered range · top 8</p>
                        </div>
                        @if (count($patPerCaregiver) > 0)
                            <div class="relative"
                                style="height:{{ max(180, count($patPerCaregiver) * 40) }}px;">
                                <canvas id="patCaregiverChart"></canvas>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-sm text-gray-400">No patient assignment data in this range</p>
                            </div>
                        @endif
                    </div>
                </div>

            </main>
        </div>
    </div>

    {{-- ===================== SCRIPTS ===================== --}}
    <script>
        // ── Print / Export PDF ────────────────────────────────────────────
        function printReport() {
            // Freeze all Chart.js canvases to static <img> tags so they
            // survive the print rasteriser in every browser (Firefox, Safari).
            const canvases = document.querySelectorAll('canvas');
            const swaps    = [];

            canvases.forEach(canvas => {
                const img = document.createElement('img');
                img.src   = canvas.toDataURL('image/png', 1.0);
                img.style.cssText = canvas.style.cssText;
                img.style.width   = canvas.offsetWidth  + 'px';
                img.style.height  = canvas.offsetHeight + 'px';
                img.style.maxWidth = '100%';
                img.className     = canvas.className;
                canvas.parentNode.insertBefore(img, canvas);
                canvas.style.display = 'none';
                swaps.push({ canvas, img });
            });

            // Small delay so the images are painted, then print
            setTimeout(() => {
                window.print();

                // Restore canvases after the print dialog closes
                setTimeout(() => {
                    swaps.forEach(({ canvas, img }) => {
                        canvas.style.display = '';
                        img.remove();
                    });
                }, 500);
            }, 150);
        }

        // ── Sidebar toggle ────────────────────────────────────────────────
        (function () {
            var sb = document.getElementById('sidebar'),
                ov = document.getElementById('sidebar-overlay');
            if (!sb || !ov) return;
            function applyState(open) {
                sb.style.zIndex    = '9999';
                ov.style.zIndex    = '9998';
                ov.style.left      = '15rem';
                sb.style.transform = open ? 'translateX(0)' : 'translateX(-100%)';
                sb.style.translate = open ? '0 0' : '-100% 0';
                ov.style.display   = open ? 'block' : 'none';
                sb.setAttribute('data-open', open ? '1' : '0');
            }
            if (window.innerWidth < 1024) applyState(false);
            window.toggleSidebar = function () {
                applyState(sb.getAttribute('data-open') !== '1');
                document.getElementById('mainAreaDiv').setAttribute(
                    'style',
                    'margin-left:' + (sb.getAttribute('data-open') === '1' ? '200px' : '0') +
                    '; transition: margin-left 0.2s ease-in-out;'
                );
            };
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    sb.style.transform = '';
                    sb.style.translate = '';
                    ov.style.display   = '';
                } else if (sb.getAttribute('data-open') !== '1') {
                    applyState(false);
                }
            });
        }());

        // ── Date filter helpers ───────────────────────────────────────────
        function fmt(d) {
            // Returns YYYY-MM-DD from a Date object
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        function applyPreset(preset) {
            const today = new Date();
            let start, end = today;

            switch (preset) {
                case 'this_week': {
                    // Monday as start of week
                    const dow = (today.getDay() + 6) % 7; // 0=Mon
                    start = new Date(today);
                    start.setDate(today.getDate() - dow);
                    break;
                }
                case 'this_month':
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
                case 'last_3_months':
                    start = new Date(today);
                    start.setMonth(today.getMonth() - 3);
                    break;
                case 'last_6_months':
                    start = new Date(today);
                    start.setMonth(today.getMonth() - 6);
                    break;
                case 'this_year':
                    start = new Date(today.getFullYear(), 0, 1);
                    break;
                case 'all':
                    start = new Date('2020-01-01');
                    break;
                case 'custom':
                    // Just highlight the pill; let user pick dates manually
                    setActivePill('custom');
                    document.getElementById('presetField').value = 'custom';
                    return;
            }

            document.getElementById('startDate').value    = fmt(start);
            document.getElementById('endDate').value      = fmt(end);
            document.getElementById('presetField').value  = preset;
            setActivePill(preset);

            // Auto-submit (skip for 'custom')
            if (preset !== 'custom') {
                document.getElementById('filterForm').submit();
            }
        }

        function onDateChange() {
            // When user manually edits a date input, switch to custom preset
            document.getElementById('presetField').value = 'custom';
            setActivePill('custom');
        }

        function setActivePill(preset) {
            document.querySelectorAll('.preset-pill').forEach(p => p.classList.remove('active'));
            const el = document.getElementById('pill-' + preset);
            if (el) el.classList.add('active');
        }

        // ── Chart.js defaults ─────────────────────────────────────────────
        Chart.defaults.font.family      = "'Instrument Sans', sans-serif";
        Chart.defaults.font.size        = 12;
        Chart.defaults.color            = '#6b7280';
        // Zero-duration animation so canvases are always fully drawn
        // before the user clicks "Export PDF"
        Chart.defaults.animation        = { duration: 0 };
        Chart.defaults.transitions      = {};

        // ── Trend chart ───────────────────────────────────────────────────
        (function () {
            const ctx = document.getElementById('trendChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($monthLabels),
                    datasets: [
                        {
                            label: 'Activities',
                            data:  @json($monthCounts),
                            borderColor:     '#2dd4bf',
                            backgroundColor: 'rgba(45,212,191,0.12)',
                            borderWidth: 2.5,
                            pointRadius: 4,
                            pointBackgroundColor: '#2dd4bf',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Alerts',
                            data:  @json($alertCounts),
                            borderColor:     '#fca5a5',
                            backgroundColor: 'rgba(252,165,165,0.10)',
                            borderWidth: 2.5,
                            pointRadius: 4,
                            pointBackgroundColor: '#fca5a5',
                            tension: 0.4,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            padding: 10,
                            cornerRadius: 10,
                        },
                    },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af' } },
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: '#f3f4f6' },
                            border: { display: false },
                        },
                    },
                },
            });
        }());

        // ── Age doughnut ──────────────────────────────────────────────────
        (function () {
            const ctx = document.getElementById('ageChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($ageGroups)),
                    datasets: [{
                        data: @json(array_values($ageGroups)),
                        backgroundColor: ['#2dd4bf','#818cf8','#fb923c','#34d399','#f472b6','#94a3b8'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            padding: 10,
                            cornerRadius: 10,
                        },
                    },
                },
            });
        }());

        // ── Activities per caregiver ──────────────────────────────────────
        (function () {
            const ctx = document.getElementById('actCaregiverChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($actPerCaregiver)),
                    datasets: [{
                        label: 'Activities',
                        data:  @json(array_values($actPerCaregiver)),
                        backgroundColor: 'rgba(45,212,191,0.75)',
                        borderWidth: 0,
                        borderRadius: 6,
                        borderSkipped: false,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            padding: 10,
                            cornerRadius: 10,
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: '#f3f4f6' },
                            border: { display: false },
                        },
                        y: { grid: { display: false }, border: { display: false }, ticks: { color: '#374151' } },
                    },
                },
            });
        }());

        // ── Patients per caregiver ────────────────────────────────────────
        (function () {
            const ctx = document.getElementById('patCaregiverChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($patPerCaregiver)),
                    datasets: [{
                        label: 'Patients',
                        data:  @json(array_values($patPerCaregiver)),
                        backgroundColor: 'rgba(129,140,248,0.75)',
                        borderWidth: 0,
                        borderRadius: 6,
                        borderSkipped: false,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            padding: 10,
                            cornerRadius: 10,
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: '#f3f4f6' },
                            border: { display: false },
                        },
                        y: { grid: { display: false }, border: { display: false }, ticks: { color: '#374151' } },
                    },
                },
            });
        }());
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                position: 'top-end', icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false, timer: 1800, toast: true,
            });
        </script>
    @endif

</body>

</html>
