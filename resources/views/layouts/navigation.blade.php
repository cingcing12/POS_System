<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden"></div>

<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-slate-900 lg:translate-x-0 lg:static lg:inset-0 shadow-2xl">
    
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="text-2xl font-bold text-white tracking-wide">POS<span class="text-blue-500">System</span></span>
        </div>
    </div>

    <div class="flex items-center px-6 mt-8 pb-6 border-b border-slate-800">
        <div class="relative">
            @if(Auth::user()->photo_url)
                <img src="{{ asset(Auth::user()->photo_url) }}" class="w-12 h-12 rounded-full object-cover border-2 border-slate-700">
            @else
                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-slate-900 rounded-full"></div>
        </div>
        <div class="mx-3">
            <p class="text-sm font-bold text-white truncate max-w-[100px]">{{ Auth::user()->name }}</p>
            <div class="mt-1">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                    {{ Auth::user()->role === 'admin' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                    {{ Auth::user()->role === 'sale' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : '' }}
                    {{ Auth::user()->role === 'stock' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}">
                    {{ Auth::user()->role }}
                </span>
            </div>
        </div>
    </div>

    <nav class="mt-6 px-4 space-y-2">
        
        {{-- UPDATED: Dashboard accessible by Admin AND Stock --}}
        @if(in_array(Auth::user()->role, ['admin', 'stock']))
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="mx-4 font-medium">Dashboard</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'sale']))
        <a href="{{ route('pos.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('pos.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            <span class="mx-4 font-medium">POS Terminal</span>
        </a>
        @endif

        @if(in_array(Auth::user()->role, ['admin', 'stock']))
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Inventory</p>
        </div>

        <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span class="mx-4 font-medium">Products</span>
        </a>

        <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span class="mx-4 font-medium">Categories</span>
        </a>
        @endif

        @if(Auth::user()->role === 'admin')
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">HR & People</p>
        </div>
        
        <a href="{{ route('staff.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('staff.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="mx-4 font-medium">Staff Management</span>
        </a>
        @endif
        
        <a href="{{ route('attendance.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('attendance.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="mx-4 font-medium">Attendance</span>
        </a>

        @if(in_array(Auth::user()->role, ['sale', 'stock']))
        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('profile.edit') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="mx-4 font-medium">My Profile</span>
        </a>
        @endif

        @if(Auth::user()->role === 'admin')
        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">System</p>
        </div>

        <a href="{{ route('admin.history') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.history') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="mx-4 font-medium">Activity History</span>
        </a>

        <a href="{{ route('reports.sales') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="mx-4 font-medium">Sales Reports</span>
        </a>
        @endif

        <div class="mt-8 pt-6 border-t border-slate-800 mb-6">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="#" onclick="confirmLogout(event)" class="flex items-center px-4 py-3 text-red-400 transition-colors duration-200 hover:bg-red-500/10 hover:text-red-300 rounded-xl group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="mx-4 font-medium">Log Out</span>
                </a>
            </form>
        </div>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout(event) {
        event.preventDefault(); // Stop immediate submission

        Swal.fire({
            title: 'Ready to leave?',
            text: "You will be returned to the login screen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Tailwind red-500
            cancelButtonColor: '#1e293b', // Tailwind slate-800
            confirmButtonText: 'Yes, Log Out',
            cancelButtonText: 'Cancel',
            background: '#ffffff',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl shadow-2xl',
                title: 'font-bold text-slate-800',
                htmlContainer: 'text-slate-500 font-medium',
                confirmButton: 'px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-red-200 transition-transform active:scale-95',
                cancelButton: 'px-6 py-2.5 rounded-xl font-bold text-slate-400 hover:text-white transition-transform active:scale-95'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>