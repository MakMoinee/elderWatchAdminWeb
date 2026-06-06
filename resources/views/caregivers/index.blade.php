<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caregivers — ElderWatch</title>
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
                'label' => 'Schedule',
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
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60 overflow-hidden" style="margin-left:200px;">

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
            <main class="flex-1 overflow-y-auto p-6 space-y-5">

                {{-- Page header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Caregivers</h2>
                        <p class="text-sm text-gray-500 mt-0.5">
                            {{ $totalCaregivers }} registered caregiver{{ $totalCaregivers !== 1 ? 's' : '' }} in the
                            system
                        </p>
                    </div>
                    <a href="{{ route('caregivers.create') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold
                           px-4 py-2.5 rounded-xl hover:bg-blue-700 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Caregiver
                    </a>
                </div>

                {{-- Search & filter bar --}}
                <div class="bg-white rounded-2xl border border-gray-100 px-4 py-3 flex flex-col sm:flex-row gap-3"
                    style="margin-top:10px;">
                    <div class="relative flex-1">
                        <input id="searchInput" type="text" placeholder="Search by name or email…"
                            oninput="filterTable()"
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div class="text-sm text-gray-400 flex items-center shrink-0">
                        <span
                            id="visibleCount">{{ $totalCaregivers }}</span>&nbsp;result{{ $totalCaregivers !== 1 ? 's' : '' }}
                    </div>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden" style="margin-top:10px;">
                    @if ($totalCaregivers > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm" id="caregiverTable">
                                <thead>
                                    <tr class="border-b border-gray-100 bg-gray-50/60">
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Caregiver</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Email</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Phone</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Address</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Registered</th>
                                        <th
                                            class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Patients</th>
                                        <th
                                            class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50" id="caregiverTableBody">
                                    @foreach ($caregivers as $caregiver)
                                        @php
                                            $fullName = trim(
                                                ($caregiver['firstName'] ?? '') .
                                                    ' ' .
                                                    ($caregiver['middleName'] ?? '') .
                                                    ' ' .
                                                    ($caregiver['lastName'] ?? ''),
                                            );
                                            $initChar = strtoupper(substr($caregiver['firstName'] ?? 'C', 0, 1));
                                            $email = $caregiver['email'] ?? '—';
                                            $phone = $caregiver['phoneNumber'] ?? '—';
                                            $address = $caregiver['address'] ?? '—';
                                            $regDate = $caregiver['registeredDate'] ?? null;
                                            $docId = $caregiver['docID'] ?? '';
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors caregiver-row"
                                            data-name="{{ strtolower($fullName) }}"
                                            data-email="{{ strtolower($email) }}">

                                            {{-- Avatar + Name --}}
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                                                        <span
                                                            class="text-sm font-semibold text-purple-600">{{ $initChar }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $fullName ?: '—' }}
                                                        </p>
                                                        <p class="text-xs text-gray-400">Caregiver</p>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Email --}}
                                            <td class="px-6 py-4 text-gray-600">{{ $email }}</td>

                                            {{-- Phone --}}
                                            <td class="px-6 py-4 text-gray-600">{{ $phone }}</td>

                                            {{-- Address --}}
                                            <td class="px-6 py-4 text-gray-600 max-w-[160px] truncate"
                                                title="{{ $address }}">
                                                {{ $address }}
                                            </td>

                                            {{-- Registered Date --}}
                                            <td class="px-6 py-4 text-gray-500 text-xs">
                                                {{ $regDate ? \Carbon\Carbon::parse($regDate)->format('M d, Y') : '—' }}
                                            </td>

                                            {{-- Assigned Patients --}}
                                            <td class="px-6 py-4">
                                                @php $assignedPatients = $caregiverPatientMap[$docId] ?? []; @endphp
                                                @if (count($assignedPatients) > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach (array_slice($assignedPatients, 0, 2) as $pName)
                                                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-red-50 text-red-600">
                                                                {{ $pName }}
                                                            </span>
                                                        @endforeach
                                                        @if (count($assignedPatients) > 2)
                                                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                                                                +{{ count($assignedPatients) - 2 }} more
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400">None assigned</span>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    {{-- Edit --}}
                                                    <a href="{{ route('caregivers.edit', $docId) }}"
                                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600
                                                           border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    {{-- Delete --}}
                                                    <button
                                                        onclick="confirmDelete('{{ $docId }}', '{{ addslashes($fullName) }}')"
                                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600
                                                           border border-red-100 px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Table footer --}}
                        <div class="px-6 py-3 border-t border-gray-50 flex items-center justify-between">
                            <p class="text-xs text-gray-400">
                                Showing <span id="shownCount">{{ $totalCaregivers }}</span> of {{ $totalCaregivers }}
                                caregivers
                            </p>
                        </div>
                    @else
                        {{-- Empty state --}}
                        <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                            <div class="w-16 h-16 rounded-2xl bg-purple-50 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-purple-300" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">No caregivers yet</h3>
                            <p class="text-sm text-gray-400 mb-6">Get started by adding the first caregiver account.
                            </p>
                            <a href="{{ route('caregivers.create') }}"
                                class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold
                                   px-4 py-2.5 rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add First Caregiver
                            </a>
                        </div>
                    @endif
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

        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.caregiver-row');
            let visible = 0;

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const match = name.includes(q) || email.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            document.getElementById('visibleCount').textContent = visible;
            document.getElementById('shownCount').textContent = visible;
        }

        function confirmDelete(docId, name) {
            Swal.fire({
                title: 'Delete caregiver?',
                html: `<p class="text-sm text-gray-500">You are about to remove <strong>${name}</strong>. This cannot be undone.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/caregivers/${docId}`;
                    form.submit();
                }
            });
        }
    </script>

    @if (session('success'))
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
