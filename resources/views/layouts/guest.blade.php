<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Login') — RailTender Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 antialiased">
    <div class="min-h-screen flex">

        {{-- Left Panel — Branding --}}
        <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 relative overflow-hidden flex-col justify-between p-12">
            {{-- Background grid pattern --}}
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>
            {{-- Decorative blurs --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600 rounded-full opacity-10 blur-3xl"></div>

            {{-- Logo --}}
            <div class="relative">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-white font-bold text-xl">RailTender</span>
                        <span class="text-blue-400 font-bold text-xl"> Pro</span>
                    </div>
                </div>
            </div>

            {{-- Hero Text --}}
            <div class="relative">
                <h2 class="text-4xl xl:text-5xl font-bold text-white leading-tight mb-6">
                    Manage Your Railway<br>
                    <span class="text-blue-400">Tenders Effortlessly</span>
                </h2>
                <p class="text-slate-400 text-lg leading-relaxed mb-10 max-w-lg">
                    A complete tender management platform — create, publish, and award tenders with full bid tracking and role-based access control.
                </p>

                {{-- Feature list --}}
                <div class="space-y-4">
                    @foreach([
                        ['Full tender lifecycle management', 'bg-blue-500/20 text-blue-400'],
                        ['Real-time bid submission & tracking', 'bg-emerald-500/20 text-emerald-400'],
                        ['Role-based access for teams', 'bg-violet-500/20 text-violet-400'],
                    ] as [$text, $iconClass])
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 {{ $iconClass }} rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-slate-300 text-sm">{{ $text }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bottom tagline --}}
            <div class="relative">
                <p class="text-slate-500 text-sm">Trusted by railway contractors across India</p>
            </div>
        </div>

        {{-- Right Panel — Form --}}
        <div class="flex-1 flex flex-col justify-center bg-slate-50 px-6 py-12 lg:px-12 xl:px-16 relative overflow-hidden">

            {{-- Subtle background decoration --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-100 rounded-full opacity-30 blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-100 rounded-full opacity-30 blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 flex items-center gap-3 relative">
                <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-slate-900 font-bold text-lg">RailTender <span class="text-blue-600">Pro</span></span>
            </div>

            {{-- Form Container --}}
            <div class="w-full max-w-sm mx-auto lg:mx-0 relative">
                @yield('content')
            </div>

            {{-- Bottom secure badge --}}
            <div class="w-full max-w-sm mx-auto lg:mx-0 mt-8 relative">
                <div class="flex items-center justify-center gap-2 text-xs text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Secured with 256-bit SSL encryption</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
