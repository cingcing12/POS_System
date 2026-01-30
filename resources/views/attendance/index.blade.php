<x-app-layout>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-header { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="toastNotification()" 
         x-init="initSession('{{ session('success') }}', '{{ session('error') }}')" 
         class="relative z-[100]">
        
        <div class="fixed top-6 right-6 flex flex-col gap-3 w-full max-w-sm pointer-events-none">
            <div x-show="show" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-2xl shadow-lg ring-1 ring-black ring-opacity-5 bg-white/90 backdrop-blur-xl border border-white/50"
                 style="display: none;">
                <div class="p-4">
                    <div class="flex items-start">
                        <div x-show="type === 'success'" class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div x-show="type === 'error'" class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-black text-slate-900" x-text="title"></p>
                            <p class="mt-1 text-sm text-slate-500 font-medium leading-relaxed" x-text="message"></p>
                        </div>
                        <div class="ml-4 flex flex-shrink-0">
                            <button @click="show = false" class="inline-flex rounded-md bg-white text-slate-400 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="h-1 w-full bg-slate-100">
                    <div class="h-full transition-all duration-[3000ms] ease-linear w-0" 
                         :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
                         :style="show ? 'width: 100%' : 'width: 0%'"></div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
        <div class="py-12 bg-gray-50 min-h-screen" x-data="{ showExportModal: false, exportType: 'today' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <h2 class="text-4xl font-black text-slate-900 tracking-tight">Staff Attendance</h2>
                        <p class="mt-2 text-slate-500 font-medium">Monitor real-time shifts and historical logs.</p>
                    </div>
                    <div class="flex gap-3">
                         <button @click="showExportModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 flex items-center gap-2 transition-all hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Export PDF</span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Staff</p>
                            <h3 class="text-4xl font-black text-slate-900 mt-1">{{ $workingNow->count() }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Check-ins</p>
                            <h3 class="text-4xl font-black text-slate-900 mt-1">{{ $totalPresent }}</h3>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Late Arrivals</p>
                            <h3 class="text-4xl font-black text-slate-900 mt-1">{{ $totalLate }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-2 rounded-[1.5rem] shadow-sm border border-slate-200 mb-8">
                    <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 p-2">
                        
                        <div class="relative flex-1 w-full group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search staff name..." class="block w-full pl-10 pr-3 py-2.5 border-none rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium transition-all">
                        </div>

                        <div class="w-full md:w-auto">
                            <input type="date" name="date" value="{{ request('date') }}" class="block w-full py-2.5 px-4 border-none rounded-xl bg-slate-50 text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                        </div>

                        <button type="submit" class="w-full md:w-auto bg-slate-900 hover:bg-black text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-md">
                            Search
                        </button>
                        
                        @if(request()->has('search') || request()->has('date'))
                            <a href="{{ route('attendance.index') }}" class="w-full md:w-auto text-center bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-bold transition-all">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-widest text-slate-400">
                                    <th class="px-8 py-5 font-bold">Employee</th>
                                    <th class="px-8 py-5 font-bold">Date</th>
                                    <th class="px-8 py-5 font-bold">Clock In</th>
                                    <th class="px-8 py-5 font-bold">Clock Out</th>
                                    <th class="px-8 py-5 font-bold text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($allHistory as $record)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-4">
                                            @if($record->user->photo_url)
                                                <img src="{{ asset($record->user->photo_url) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                                    {{ substr($record->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-slate-900">{{ $record->user->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $record->user->role }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-sm font-bold text-slate-600">
                                        {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-8 py-4 font-mono text-sm font-bold text-emerald-600">
                                        {{ \Carbon\Carbon::parse($record->check_in)->format('h:i A') }}
                                    </td>
                                    <td class="px-8 py-4 font-mono text-sm font-bold text-slate-400">
                                        {{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : '--:--' }}
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide 
                                            {{ $record->status === 'Late' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                                            {{ $record->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-slate-400">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        <p class="font-bold text-lg">No records found.</p>
                                        <p class="text-sm">Try adjusting your filters.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-slate-100">
                        {{ $allHistory->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <div x-show="showExportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showExportModal = false"></div>

                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <div class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg" 
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0">
                        
                        <div class="bg-indigo-600 px-8 py-6 flex justify-between items-center relative overflow-hidden">
                             <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                             <div class="relative z-10 text-white">
                                <h2 class="text-xl font-black">Export Report</h2>
                                <p class="text-indigo-200 text-sm mt-0.5">Select the data range you need.</p>
                             </div>
                             <button @click="showExportModal = false" class="relative z-10 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                             </button>
                        </div>

                        <div class="p-8">
                            <div class="flex bg-slate-100 p-1 rounded-xl mb-6">
                                <button @click="exportType = 'today'" :class="exportType === 'today' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 rounded-lg text-sm font-bold transition-all">Today</button>
                                <button @click="exportType = 'monthly'" :class="exportType === 'monthly' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 rounded-lg text-sm font-bold transition-all">Monthly</button>
                                <button @click="exportType = 'yearly'" :class="exportType === 'yearly' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 rounded-lg text-sm font-bold transition-all">Yearly</button>
                            </div>

                            <form action="{{ route('attendance.export') }}" method="GET">
                                
                                <div x-show="exportType === 'today'" class="text-center py-4">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 mb-3">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">Daily Report</h3>
                                    <p class="text-slate-500 text-sm mt-1">Export attendance for <span class="font-bold text-slate-900">{{ date('F d, Y') }}</span></p>
                                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}" :disabled="exportType !== 'today'">
                                </div>

                                <div x-show="exportType === 'monthly'" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 text-left ml-1">Select Month</label>
                                        <select name="month" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-indigo-500 font-bold text-slate-700" :required="exportType === 'monthly'" :disabled="exportType !== 'monthly'">
                                            @foreach(range(1, 12) as $m)
                                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 text-left ml-1">Select Year</label>
                                        <select name="year" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-indigo-500 font-bold text-slate-700" :required="exportType === 'monthly'" :disabled="exportType !== 'monthly'">
                                            @foreach(range(date('Y'), date('Y')-5) as $y)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div x-show="exportType === 'yearly'" class="space-y-4">
                                    <div class="text-center mb-4">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 text-blue-600 mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        </div>
                                        <p class="text-sm text-slate-500">Full annual summary for all staff.</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 text-left ml-1">Select Year</label>
                                        <select name="year" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-indigo-500 font-bold text-slate-700" :required="exportType === 'yearly'" :disabled="exportType !== 'yearly'">
                                            @foreach(range(date('Y'), date('Y')-5) as $y)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                                    <button type="button" @click="showExportModal = false" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-200 transition-all transform active:scale-95">Download PDF</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @else
    <div class="py-10 bg-[#F3F4F6] min-h-screen" x-data="attendanceClock()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Time Clock</h1>
                    <p class="text-slate-500 font-medium mt-1">Manage your daily work shifts.</p>
                </div>
                <div class="bg-white px-5 py-2 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-3">
                    <div class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Current Time</p>
                        <p class="text-xl font-mono font-black text-slate-800 leading-none" x-text="time">00:00:00</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-5 xl:col-span-4">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden relative border border-slate-100 h-full flex flex-col">
                        <div class="bg-slate-900 p-8 text-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-700 opacity-20"></div>
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full blur-[60px] opacity-30 animate-pulse"></div>
                            
                            <div class="relative z-10">
                                <p class="text-indigo-300 text-xs font-bold uppercase tracking-[0.2em] mb-2">Current Local Time</p>
                                <h1 class="text-5xl font-black text-white tracking-widest font-mono tabular-nums mb-2 drop-shadow-lg" x-text="time">00:00:00</h1>
                                <div class="inline-block bg-white/10 backdrop-blur-md rounded-full px-4 py-1 text-xs font-bold text-white border border-white/10">
                                    {{ date('l, d F Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="p-8 flex-1 flex flex-col justify-center">
                            @php
                                $todayDay = strtolower(date('D'));
                                $userSchedule = auth()->user()->week_schedule;
                                $shift = $userSchedule[$todayDay] ?? 'Off';
                                $canCheckIn = true;
                                $btnText = "CLOCK IN";
                                $subText = "Start your shift";
                                $statusColor = "bg-slate-900 hover:bg-black"; // Default active color

                                // UX Logic
                                if ($shift === 'Off') {
                                    $canCheckIn = false;
                                    $btnText = "DAY OFF";
                                    $subText = "Enjoy your rest day!";
                                    $statusColor = "bg-slate-200 text-slate-400 cursor-not-allowed";
                                } elseif ($shift === 'Evening' && now()->format('H:i') < '12:00') {
                                    $canCheckIn = false;
                                    $btnText = "TOO EARLY";
                                    $subText = "Shift starts at 1:00 PM";
                                    $statusColor = "bg-orange-100 text-orange-400 cursor-not-allowed border-none";
                                } elseif ($shift === 'Morning' && now()->format('H:i') > '11:00') {
                                    $canCheckIn = false;
                                    $btnText = "SHIFT MISSED";
                                    $subText = "Please contact admin";
                                    $statusColor = "bg-red-50 text-red-400 cursor-not-allowed";
                                }
                            @endphp

                            @if(!$today)
                                <div class="text-center mb-8">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 mb-4 animate-bounce">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-800">Hello, {{ Auth::user()->name }}!</h3>
                                    <p class="text-sm text-slate-500 mt-1 font-medium">Scheduled: <span class="text-indigo-600">{{ $shift }} Shift</span></p>
                                </div>
                                
                                <form action="{{ route('attendance.checkin') }}" method="POST">
                                    @csrf
                                    <button class="w-full py-5 rounded-2xl font-black text-lg shadow-xl shadow-indigo-200 transition-all transform active:scale-95 flex flex-col items-center justify-center gap-1 {{ $canCheckIn ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : $statusColor }}" {{ !$canCheckIn ? 'disabled' : '' }}>
                                        <span>{{ $btnText }}</span>
                                        @if(!$canCheckIn)
                                            <span class="text-[10px] font-medium opacity-80 uppercase tracking-wide">{{ $subText }}</span>
                                        @endif
                                    </button>
                                </form>

                            @elseif($today->check_in && !$today->check_out)
                                <div class="text-center mb-8 relative">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-10">
                                        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_w51pcehl.json" background="transparent" speed="1" style="width: 200px; height: 200px;" loop autoplay></lottie-player>
                                    </div>
                                    <div class="relative z-10">
                                        <h3 class="text-2xl font-black text-emerald-600 mb-1">You are clocked in!</h3>
                                        <p class="text-slate-400 font-medium text-sm">Since {{ \Carbon\Carbon::parse($today->check_in)->format('h:i A') }}</p>
                                        <div class="mt-4 inline-block px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                            Currently Active
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('attendance.checkout') }}" method="POST">
                                    @csrf
                                    <button class="w-full py-5 rounded-2xl bg-white border-2 border-red-50 text-red-500 font-black text-lg hover:bg-red-500 hover:text-white hover:border-red-500 shadow-sm hover:shadow-xl hover:shadow-red-200 transition-all transform active:scale-95 flex items-center justify-center gap-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        END SHIFT
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 mb-6">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800">All Done!</h3>
                                    <p class="text-slate-500 mt-2 font-medium">You've completed your shift for today.</p>
                                    <div class="mt-6 p-4 bg-slate-50 rounded-2xl text-left border border-slate-100">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-bold text-slate-400 uppercase">Clock In</span>
                                            <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($today->check_in)->format('h:i A') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-bold text-slate-400 uppercase">Clock Out</span>
                                            <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($today->check_out)->format('h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 xl:col-span-8 space-y-8">
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">This Week</p>
                            <p class="text-3xl font-black text-indigo-600">{{ $history->count() }}</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium">Shifts Completed</p>
                        </div>
                        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">On Time</p>
                            <p class="text-3xl font-black text-emerald-500">{{ $history->where('status', 'Present')->count() }}</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium">Perfect Arrivals</p>
                        </div>
                        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hidden md:block">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Late</p>
                            <p class="text-3xl font-black text-orange-500">{{ $history->where('status', 'Late')->count() }}</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium">Needs Improvement</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-base font-bold text-slate-800">Recent Activity</h3>
                            <button class="text-xs font-bold text-indigo-500 hover:text-indigo-700 transition-colors">View All</button>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach($history as $record)
                            <div class="p-6 hover:bg-slate-50/80 transition-colors flex items-center justify-between group">
                                <div class="flex items-center gap-5">
                                    <div class="flex-shrink-0 h-14 w-14 {{ $record->status === 'Late' ? 'bg-orange-50 text-orange-500' : 'bg-indigo-50 text-indigo-600' }} rounded-2xl flex flex-col items-center justify-center border {{ $record->status === 'Late' ? 'border-orange-100' : 'border-indigo-100' }} group-hover:scale-105 transition-transform">
                                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($record->date)->format('M') }}</span>
                                        <span class="text-xl font-black leading-none">{{ \Carbon\Carbon::parse($record->date)->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($record->date)->format('l') }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $record->status === 'Late' ? 'bg-orange-500' : 'bg-emerald-500' }}"></span>
                                            <span class="text-xs font-medium text-slate-500">{{ $record->status }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-8 text-right">
                                    <div class="hidden sm:block">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Start</p>
                                        <p class="font-mono text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($record->check_in)->format('h:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">End</p>
                                        <p class="font-mono text-sm font-bold text-slate-700">{{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : '--:--' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script>
        function attendanceClock() {
            return {
                time: new Date().toLocaleTimeString('en-US', { hour12: false }),
                init() {
                    setInterval(() => {
                        this.time = new Date().toLocaleTimeString('en-US', { hour12: false });
                    }, 1000);
                }
            }
        }

        // CUSTOM TOAST LOGIC
        function toastNotification() {
            return {
                show: false,
                type: 'success',
                title: 'Success',
                message: '',
                timeout: null,
                initSession(success, error) {
                    if (success) {
                        this.trigger('success', 'Operation Successful', success);
                    } else if (error) {
                        this.trigger('error', 'Action Blocked', error);
                    }
                },
                trigger(type, title, message) {
                    this.type = type;
                    this.title = title;
                    this.message = message;
                    this.show = true;
                    
                    if (this.timeout) clearTimeout(this.timeout);
                    
                    // Auto hide after 3 seconds
                    this.timeout = setTimeout(() => {
                        this.show = false;
                    }, 3000);
                }
            }
        }
    </script>
    @endif
</x-app-layout>