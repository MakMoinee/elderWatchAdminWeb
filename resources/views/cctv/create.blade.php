<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add CCTV Device — ElderWatch</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --tw-color-blue-600: #00c4c4; }
        .bg-blue-50  { background-color: #f0fdfd !important; }
        .bg-blue-600 { background-color: #00c4c4 !important; }
        .bg-blue-700 { background-color: #00a8a8 !important; }
        .text-blue-600 { color: #00c4c4 !important; }
        .text-blue-700 { color: #00a8a8 !important; }
        .border-blue-200 { border-color: #99eded !important; }
        .hover\:bg-blue-700:hover { background-color: #00a8a8 !important; }
        .focus\:ring-blue-500:focus { --tw-ring-color: #00c4c4 !important; }
        .focus\:border-blue-500:focus { border-color: #00c4c4 !important; }
        .from-blue-600 { --tw-gradient-from: #00c4c4 !important; }
        .to-blue-700   { --tw-gradient-to:   #00a8a8 !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

{{-- ══════════════════════════════════════════════════════════ SIDEBAR ══ --}}
<aside class="fixed inset-y-0 left-0 w-60 bg-gray-900 flex flex-col z-30">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-700">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
            </svg>
        </div>
        <div>
            <p class="text-white font-bold text-sm leading-tight">ElderWatch</p>
            <p class="text-gray-400 text-xs">Admin Portal</p>
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        @php
            $navItems = [
                ['route' => 'dashboard',        'label' => 'Dashboard',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'caregivers.index', 'label' => 'Caregivers',   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'patients.index',   'label' => 'Patients',     'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['route' => 'cctv.index',       'label' => 'CCTV Devices', 'icon' => 'M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z'],
                ['route' => 'guardians.index',  'label' => 'Guardians',    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['route' => 'alerts.index',     'label' => 'Alerts',       'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                ['route' => 'reports.index',    'label' => 'Reports',      'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ];
        @endphp
        @foreach ($navItems as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ $active ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="px-4 py-4 border-t border-gray-700">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                {{ strtoupper(substr(session('admin')['name'] ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-medium truncate">{{ session('admin')['name'] ?? 'Admin' }}</p>
                <p class="text-gray-400 text-xs truncate">{{ session('admin')['email'] ?? '' }}</p>
            </div>
            <a href="{{ route('logout') }}" title="Logout" class="text-gray-400 hover:text-red-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </div>
</aside>

{{-- ═══════════════════════════════════════════════════════ MAIN CONTENT ══ --}}
<div class="lg:ml-60 min-h-screen flex flex-col">

    {{-- Top bar --}}
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-3 sticky top-0 z-20">
        <a href="{{ route('cctv.index') }}"
           class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Add CCTV Device</h1>
            <p class="text-sm text-gray-500 mt-0.5">Register a new surveillance camera</p>
        </div>
    </header>

    <main class="flex-1 p-6">
        <form action="{{ route('cctv.store') }}" method="POST" class="max-w-3xl mx-auto space-y-6">
            @csrf

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <ul class="text-sm text-red-700 space-y-0.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ── Device Info ── --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900">Device Information</h2>
                </div>

                <div class="px-6 py-5 space-y-4">
                    {{-- Device ID --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Device ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="deviceID" value="{{ old('deviceID') }}"
                               placeholder="e.g. CAM-001"
                               class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      @error('deviceID') border-red-400 @enderror"/>
                        @error('deviceID')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- IP + Status row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                IP Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ip" value="{{ old('ip') }}"
                                   placeholder="e.g. 192.168.1.100"
                                   class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg font-mono
                                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          @error('ip') border-red-400 @enderror"/>
                            @error('ip')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                    class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                           @error('status') border-red-400 @enderror">
                                <option value="online"  {{ old('status') === 'online'  ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ old('status') === 'offline' ? 'selected' : '' }}>Offline</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Username + Password row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                   placeholder="Camera username"
                                   class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg
                                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                          @error('username') border-red-400 @enderror"/>
                            @error('username')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="passwordField"
                                       placeholder="Camera password"
                                       class="w-full px-3.5 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg
                                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                              @error('password') border-red-400 @enderror"/>
                                <button type="button" onclick="togglePassword('passwordField', 'eyeIcon')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- RTSP Preview --}}
                    <div class="bg-gray-900 rounded-lg px-4 py-3">
                        <p class="text-xs text-gray-400 mb-1">RTSP Stream URL Preview</p>
                        <code id="rtspPreview" class="text-xs text-green-400 font-mono break-all">
                            rtsp://username:password@ip/stream
                        </code>
                    </div>
                </div>
            </div>

            {{-- ── Patient Linking ── --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Link to Patient</h2>
                        <p class="text-xs text-gray-500">Optional — assigns this camera to monitor a patient</p>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Patient</label>
                    <select name="patientID"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">— No patient assigned —</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient['docID'] }}"
                                    {{ old('patientID') === $patient['docID'] ? 'selected' : '' }}>
                                {{ $patient['fullName'] ?? 'Unknown' }}
                                @if (!empty($patient['deviceID']))
                                    (has device: {{ $patient['deviceID'] }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1.5">
                        Selecting a patient will update their Device ID automatically.
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('cctv.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300
                          rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                               text-sm font-medium px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Device
                </button>
            </div>
        </form>
    </main>
</div>

<script>
// ── Password toggle ─────────────────────────────────────────────────────────
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        field.type = 'password';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}

// ── RTSP live preview ────────────────────────────────────────────────────────
const rtspPreview = document.getElementById('rtspPreview');
function updateRtspPreview() {
    const ip   = document.querySelector('[name=ip]').value       || 'ip';
    const user = document.querySelector('[name=username]').value || 'username';
    const pass = document.querySelector('[name=password]').value || 'password';
    rtspPreview.textContent = `rtsp://${user}:${pass}@${ip}/stream`;
}
document.querySelector('[name=ip]').addEventListener('input', updateRtspPreview);
document.querySelector('[name=username]').addEventListener('input', updateRtspPreview);
document.querySelector('[name=password]').addEventListener('input', updateRtspPreview);
</script>
</body>
</html>
