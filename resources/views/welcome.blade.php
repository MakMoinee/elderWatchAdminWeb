<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElderWatch — Admin Portal</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="font-semibold text-lg tracking-tight">ElderWatch</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    Log in
                </a>
                <a href="{{ route('login') }}"
                   class="text-sm font-semibold bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Admin Portal
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-32 pb-20 px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                ML-Powered &middot; YOLO Algorithm &middot; Real-time Detection
            </span>
            <h1 class="text-4xl lg:text-6xl font-bold tracking-tight text-gray-900 leading-tight mb-6">
                Intelligent Monitoring for<br>
                <span class="text-blue-600">Alzheimer's Patients</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                ElderWatch uses machine learning and body-movement detection to monitor patients in real time,
                automatically alerting caregivers and guardians when incidents occur.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('login') }}"
                   class="bg-blue-600 text-white font-semibold px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    Access Admin Portal
                </a>
                <a href="#features"
                   class="border border-gray-200 text-gray-700 font-semibold px-8 py-3 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    {{-- Stats strip --}}
    <section class="border-y border-gray-100 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-2xl font-bold text-gray-900">Real-time</div>
                <div class="text-sm text-gray-500 mt-1">Body Movement Detection</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">YOLO</div>
                <div class="text-sm text-gray-500 mt-1">Detection Algorithm</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">SMS</div>
                <div class="text-sm text-gray-500 mt-1">Instant Notifications</div>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">24 / 7</div>
                <div class="text-sm text-gray-500 mt-1">CCTV Monitoring</div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-24 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Admin Capabilities</h2>
                <p class="text-gray-500 max-w-xl mx-auto">
                    A centralised portal for managing every aspect of the ElderWatch ecosystem.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                    $features = [
                        [
                            'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'color' => 'blue',
                            'title' => 'View All Reports',
                            'desc' => 'Access a complete overview of all incident and monitoring reports across every patient and caregiver in the system.',
                        ],
                        [
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                            'color' => 'purple',
                            'title' => 'Caregiver Management',
                            'desc' => 'Register and manage caregiver accounts, assign patients, and review their activity monitoring and incident reports.',
                        ],
                        [
                            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                            'color' => 'green',
                            'title' => 'Guardian Management',
                            'desc' => 'Manage guardian profiles and their access to caregiver monitoring reports and patient status information.',
                        ],
                        [
                            'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                            'color' => 'red',
                            'title' => 'Patient Info',
                            'desc' => 'Maintain detailed patient records and link them to the YOLO-powered body posture detection system for continuous monitoring.',
                        ],
                        [
                            'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.869v6.263a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z',
                            'color' => 'yellow',
                            'title' => 'CCTV Device Register',
                            'desc' => 'Register and manage CCTV devices used by caregivers for real-time patient surveillance and incident detection.',
                        ],
                        [
                            'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                            'color' => 'orange',
                            'title' => 'Real-time Alerts',
                            'desc' => 'Caregivers receive instant alert notifications for real-time detection incidents. Guardians receive SMS notifications automatically.',
                        ],
                    ];
                    $colors = [
                        'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600'],
                        'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600'],
                        'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-600'],
                        'red'    => ['bg' => 'bg-red-50',    'icon' => 'text-red-600'],
                        'yellow' => ['bg' => 'bg-yellow-50', 'icon' => 'text-yellow-600'],
                        'orange' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-600'],
                    ];
                @endphp

                @foreach ($features as $f)
                    @php $c = $colors[$f['color']]; @endphp
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-24 px-6 lg:px-8 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-gray-500 max-w-xl mx-auto">
                    A seamless flow of data between patients, caregivers, guardians, and the admin.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Patient Monitored',  'desc' => 'CCTV cameras capture body movements using the YOLO detection algorithm around the clock.'],
                        ['num' => '02', 'title' => 'Incident Detected',  'desc' => 'Machine learning models analyse posture and movement to identify falls or unusual behaviour.'],
                        ['num' => '03', 'title' => 'Caregiver Alerted',  'desc' => 'Real-time alert notifications are pushed to the assigned caregiver immediately.'],
                        ['num' => '04', 'title' => 'Guardian Notified',  'desc' => 'SMS notifications are automatically sent to the patient\'s guardian with incident details.'],
                    ];
                @endphp
                @foreach ($steps as $s)
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-sm flex items-center justify-center mx-auto mb-4">
                            {{ $s['num'] }}
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $s['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $s['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <div class="bg-blue-600 rounded-3xl px-8 py-16">
                <h2 class="text-3xl font-bold text-white mb-4">Ready to get started?</h2>
                <p class="text-blue-100 mb-8 leading-relaxed">
                    Log in to the admin portal to manage caregivers, guardians, patients, and view all monitoring reports.
                </p>
                <a href="{{ route('login') }}"
                   class="inline-block bg-white text-blue-600 font-semibold px-8 py-3 rounded-lg hover:bg-blue-50 transition-colors text-sm">
                    Go to Admin Portal
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 py-8 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-blue-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="font-medium text-gray-600">ElderWatch</span>
            </div>
            <span>&copy; {{ date('Y') }} ElderWatch. All rights reserved.</span>
        </div>
    </footer>

</body>
</html>
