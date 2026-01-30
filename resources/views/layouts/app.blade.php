<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS System') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">
        
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
            [x-cloak] { display: none !important; }
            
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            
            /* Glass Header */
            .glass-header {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(241, 245, 249, 0.8);
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden bg-[#F8FAFC]">
            
            @include('layouts.navigation')

            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                
                <header class="glass-header sticky top-0 z-40 flex items-center justify-between px-6 py-4 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-indigo-600 focus:outline-none lg:hidden transition-colors p-2 hover:bg-slate-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        </button>

                        <div class="hidden sm:block">
                            <h2 class="text-xl font-black text-slate-800 tracking-tight">
                                {{ $header ?? 'Dashboard' }}
                            </h2>
                        </div>
                    </div>

                    <div class="flex items-center gap-5">
                        <div class="hidden md:block text-right">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">{{ date('D, M d') }}</p>
                            <p class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</p>
                        </div>
                        
                        <div class="relative group cursor-pointer">
                            <div class="relative">
                                @if(Auth::user()->photo_url)
                                    <img src="{{ asset(Auth::user()->photo_url) }}" class="w-11 h-11 rounded-2xl object-cover border-[3px] border-white shadow-lg shadow-indigo-100 transition-transform group-hover:scale-105">
                                @else
                                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-lg border-[3px] border-white shadow-lg shadow-indigo-200 transition-transform group-hover:scale-105">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="w-full flex-grow p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>

            </div>
        </div>

        @stack('modals')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // If you use SweetAlert globally somewhere else
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            });
        </script>
    </body>
</html>