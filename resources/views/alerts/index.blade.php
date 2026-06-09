<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alerts — ElderWatch</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
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
                'active' => true,
                'icon' =>
                    'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            ],
        ];
    @endphp

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-40 w-60 bg-white border-r border-gray-100 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
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
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                @foreach ($navItems as $item)
                    @php
                        $exists = Route::has($item['route']);
                        $href = $exists ? route($item['route']) : '#';
                    @endphp
                    <a href="{{ $href }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ $item['active'] ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
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
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $firstName }} {{ $lastName }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">Administrator</p>
                    </div>
                    <a href="{{ route('logout') }}" class="text-gray-400 hover:text-red-500 transition-colors shrink-0">
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

        {{-- MAIN --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60 overflow-hidden" style="margin-left:200px;"
            id="mainAreaDiv">

            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0 z-20">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden lg:block">
                    <h1 class="text-sm font-semibold text-gray-900">Alerts</h1>
                    <p class="text-xs text-gray-400">Activity history and caregiver notifications</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-xs font-semibold text-white">{{ $initials }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 space-y-5">

                {{-- Page header --}}
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Alerts</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $totalAlerts }} event{{ $totalAlerts !== 1 ? 's' : '' }} recorded</p>
                </div>

                {{-- Filter dropdown --}}
                <div class="flex items-center gap-3">
                    <select id="statusFilter" onchange="applyFilters()"
                        class="text-sm border border-gray-200 rounded-xl px-3.5 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <option value="">All</option>
                        <option value="unread">Unread</option>
                        <option value="responded">Responded</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="bg-white rounded-2xl border border-gray-100 px-4 py-3 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z" />
                        </svg>
                        <input id="searchInput" type="text" placeholder="Search by caregiver or IP…"
                            oninput="filterTable()"
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div class="text-sm text-gray-400 flex items-center shrink-0">
                        <span
                            id="visibleCount">{{ $totalAlerts }}</span>&nbsp;event{{ $totalAlerts !== 1 ? 's' : '' }}
                    </div>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    @if ($totalAlerts > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 bg-gray-50/60">
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Status</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Caregiver</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            IP Address</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Timestamp</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Image</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($alerts as $alert)
                                        @php
                                            $status = strtolower($alert['status'] ?? 'unknown');
                                            $caregiverID = $alert['caregiverID'] ?? '';
                                            $caregiverName = $caregiverMap[$caregiverID] ?? '(unknown)';
                                            $ip = $alert['ip'] ?? '—';
                                            $imagePath = $alert['imagePath'] ?? null;
                                            $cgInitial = strtoupper(substr($caregiverName, 0, 1));

                                            // Parse createdAt — could be Firestore Timestamp or string
                                            $rawTs = $alert['createdAt'] ?? null;
                                            if ($rawTs instanceof \Google\Cloud\Core\Timestamp) {
                                                $timestamp = \Carbon\Carbon::createFromTimestamp(
                                                    $rawTs->get()->getTimestamp(),
                                                );
                                            } elseif ($rawTs) {
                                                try {
                                                    $timestamp = \Carbon\Carbon::parse((string) $rawTs);
                                                } catch (\Exception $e) {
                                                    $timestamp = null;
                                                }
                                            } else {
                                                $timestamp = null;
                                            }

                                            $statusColors = [
                                                'alert' => [
                                                    'bg' => 'bg-red-50',
                                                    'text' => 'text-red-700',
                                                    'dot' => 'bg-red-500',
                                                ],
                                                'normal' => [
                                                    'bg' => 'bg-green-50',
                                                    'text' => 'text-green-700',
                                                    'dot' => 'bg-green-500',
                                                ],
                                                'verified' => [
                                                    'bg' => 'bg-blue-50',
                                                    'text' => 'text-blue-700',
                                                    'dot' => 'bg-blue-500',
                                                ],
                                                'responded' => [
                                                    'bg' => 'bg-blue-50',
                                                    'text' => 'text-blue-700',
                                                    'dot' => 'bg-blue-500',
                                                ],
                                            ];
                                            $colors = $statusColors[$status] ?? [
                                                'bg' => 'bg-gray-100',
                                                'text' => 'text-gray-600',
                                                'dot' => 'bg-gray-400',
                                            ];
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors alert-row"
                                            data-search="{{ strtolower($caregiverName . ' ' . $ip) }}"
                                            data-status="{{ $status }}">

                                            {{-- Status --}}
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full {{ $colors['dot'] }} {{ $status === 'alert' ? 'animate-pulse' : '' }}"></span>
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>

                                            {{-- Caregiver --}}
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2.5">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                                        <span
                                                            class="text-xs font-semibold text-blue-600">{{ $cgInitial }}</span>
                                                    </div>
                                                    <span
                                                        class="font-medium text-gray-900">{{ $caregiverName }}</span>
                                                </div>
                                            </td>

                                            {{-- IP --}}
                                            <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $ip }}
                                            </td>

                                            {{-- Timestamp --}}
                                            <td class="px-6 py-4 text-gray-600 text-xs">
                                                @if ($timestamp)
                                                    <div>{{ $timestamp->format('M d, Y') }}</div>
                                                    <div class="text-gray-400">{{ $timestamp->format('h:i A') }}</div>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Image --}}
                                            <td class="px-6 py-4">
                                                <button onclick="showImage('{{ addslashes($imagePath ?? '') }}')"
                                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-3 border-t border-gray-50">
                            <p class="text-xs text-gray-400">Showing <span id="shownCount">{{ $totalAlerts }}</span>
                                of {{ $totalAlerts }} events</p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                            <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-green-300" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">No alerts yet</h3>
                            <p class="text-sm text-gray-400">Activity events will appear here when caregivers check in.
                            </p>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    {{-- Image preview modal --}}
    <div id="imageModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-lg w-full">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <p class="font-semibold text-gray-900 text-sm">Activity Image</p>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                {{-- Actual image (shown when path exists) --}}
                <img id="modalImage" src="" alt="Activity capture"
                    class="w-full rounded-xl object-cover max-h-80 hidden" />

                {{-- No-image placeholder --}}
                <div id="modalNoImage" class="hidden flex-col items-center justify-center py-10 text-center">
                    <div style="width:64px;height:64px;border-radius:16px;background:#f9fafb;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <svg style="width:32px;height:32px;color:#d1d5db;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">No image uploaded</p>
                    <p class="text-xs text-gray-400 mt-1">This alert does not have an associated image yet.</p>
                </div>
            </div>
            <div class="px-4 pb-4">
                <p id="modalImagePath" class="text-xs text-gray-400 font-mono truncate"></p>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var sb = document.getElementById('sidebar'),
                ov = document.getElementById('sidebar-overlay');
            if (!sb || !ov) return;

            function applyState(open) {
                sb.style.zIndex    = '9999'; // always above overlay regardless of Tailwind CSS
                ov.style.zIndex    = '9998';
                ov.style.left      = '15rem'; // never cover sidebar (w-60) so nav items stay clickable
                sb.style.transform = open ? 'translateX(0)' : 'translateX(-100%)';
                sb.style.translate = open ? '0 0' : '-100% 0';
                ov.style.display   = open ? 'block' : 'none';
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

        function applyFilters() {
            const q      = document.getElementById('searchInput').value.toLowerCase().trim();
            const status = document.getElementById('statusFilter').value;
            const rows   = document.querySelectorAll('.alert-row');
            let visible  = 0;
            rows.forEach(row => {
                const matchSearch = !q      || row.dataset.search.includes(q);
                const matchStatus = !status || row.dataset.status === status;
                const show = matchSearch && matchStatus;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            document.getElementById('visibleCount').textContent = visible;
            document.getElementById('shownCount').textContent   = visible;
        }

        function filterTable() { applyFilters(); }

        function resolveImagePath(raw) {
            if (!raw) return null;
            // Strip ./gallery/ prefix from the other server and map to our local storage path
            var filename = raw.replace(/^\.\/gallery\//, '');
            // If it already starts with /storage or http, use as-is after stripping ./gallery/
            if (filename.startsWith('/storage') || filename.startsWith('http')) {
                return filename;
            }
            return '/storage/alerts/' + filename;
        }

        function showImage(rawPath) {
            var img       = document.getElementById('modalImage');
            var noImg     = document.getElementById('modalNoImage');
            var pathLabel = document.getElementById('modalImagePath');
            var resolved  = resolveImagePath(rawPath);

            if (resolved) {
                img.src = resolved;
                img.classList.remove('hidden');
                noImg.style.display = 'none';
                pathLabel.textContent = resolved;

                // Fallback if image fails to load
                img.onerror = function () {
                    img.classList.add('hidden');
                    noImg.style.display = 'flex';
                    pathLabel.textContent = resolved + ' (could not load)';
                };
            } else {
                img.src = '';
                img.classList.add('hidden');
                noImg.style.display = 'flex';
                pathLabel.textContent = '';
            }

            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
            document.getElementById('modalImage').src = '';
            document.getElementById('modalNoImage').style.display = 'none';
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) closeImageModal();
        });
    </script>
</body>

</html>
