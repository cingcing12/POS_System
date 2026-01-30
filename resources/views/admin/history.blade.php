<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">System Activity</h2>
                    <p class="text-slate-500 font-medium mt-1">Real-time audit log of all system events.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-sm font-bold text-slate-600">Live Monitoring</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 mb-8 flex flex-col md:flex-row gap-2">
                <div class="relative flex-1">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <form method="GET" action="{{ route('admin.history') }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user, action, or details..." 
                               class="w-full pl-12 pr-4 py-3 bg-transparent border-none focus:ring-0 text-slate-700 font-bold placeholder:text-slate-400">
                    </form>
                </div>
                <div class="flex gap-2">
                    <select class="bg-slate-50 border-transparent rounded-xl px-4 py-2 text-sm font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all cursor-pointer" onchange="window.location.href='?role='+this.value">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                        <option value="stock" {{ request('role') == 'stock' ? 'selected' : '' }}>Stock Staff</option>
                        <option value="sale" {{ request('role') == 'sale' ? 'selected' : '' }}>Sale Staff</option>
                    </select>
                </div>
            </div>

            <div class="space-y-6 relative">
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-slate-200 hidden md:block"></div>

                @forelse($logs as $log)
                @php
                    // Dynamic Colors based on Action
                    $color = 'blue'; // Default
                    $icon = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'; // Info icon

                    if (str_contains(strtolower($log->action), 'created') || str_contains(strtolower($log->action), 'added')) {
                        $color = 'emerald';
                        $icon = 'M12 4v16m8-8H4'; // Plus
                    } elseif (str_contains(strtolower($log->action), 'deleted') || str_contains(strtolower($log->action), 'removed')) {
                        $color = 'rose';
                        $icon = 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'; // Trash
                    } elseif (str_contains(strtolower($log->action), 'updated') || str_contains(strtolower($log->action), 'edited')) {
                        $color = 'amber';
                        $icon = 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'; // Edit
                    }
                @endphp

                <div class="relative flex flex-col md:flex-row gap-6 group">
                    
                    <div class="md:w-32 text-right pt-3 hidden md:block">
                        <p class="text-sm font-bold text-slate-800">{{ $log->created_at->format('h:i A') }}</p>
                        <p class="text-xs font-medium text-slate-400">{{ $log->created_at->format('M d') }}</p>
                    </div>

                    <div class="absolute left-8 md:static flex-shrink-0 z-10">
                        <div class="w-10 h-10 md:w-16 md:h-16 rounded-2xl bg-white border-4 border-slate-50 shadow-sm flex items-center justify-center text-{{ $color }}-500">
                            <div class="w-10 h-10 bg-{{ $color }}-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 bg-white p-5 rounded-2xl shadow-sm border border-slate-100 group-hover:border-{{ $color }}-200 transition-all ml-12 md:ml-0">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide bg-{{ $color }}-50 text-{{ $color }}-600 border border-{{ $color }}-100">
                                    {{ $log->action }}
                                </span>
                                <span class="md:hidden text-xs text-slate-400 font-medium">• {{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border border-slate-200 px-2 py-0.5 rounded bg-slate-50">
                                {{ $log->user_role }}
                            </span>
                        </div>

                        <p class="text-slate-600 text-sm font-medium leading-relaxed mb-4">
                            {{ $log->description }}
                        </p>

                        <div class="flex items-center gap-3 pt-3 border-t border-slate-50">
                            @if($log->user && $log->user->photo_url)
                                <img src="{{ asset($log->user->photo_url) }}" class="w-6 h-6 rounded-full object-cover">
                            @else
                                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                    {{ substr($log->user->name ?? '?', 0, 1) }}
                                </div>
                            @endif
                            <p class="text-xs font-bold text-slate-500">
                                Performed by <span class="text-slate-900">{{ $log->user->name ?? 'Unknown User' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No Activity Yet</h3>
                    <p class="text-slate-400 text-sm">System events will appear here.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>