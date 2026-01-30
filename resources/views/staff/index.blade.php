<x-app-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .font-khmer { font-family: 'Kantumruy Pro', sans-serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <div x-data="staffManager()" 
         x-init="initSession('{{ session('success') }}', {{ $errors->any() ? 'true' : 'false' }}, '{{ $errors->first() }}', {{ session('new_staff_data') ? json_encode(session('new_staff_data')) : 'null' }})"
         class="min-h-screen bg-[#F3F4F6] py-10 font-inter relative">

        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
            <div x-show="toast.show" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-2xl shadow-xl bg-white/95 backdrop-blur-xl border border-white/50"
                 style="display: none;">
                <div class="p-4 flex items-start gap-4">
                    <div class="shrink-0">
                        <div x-show="toast.type === 'success'" class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div x-show="toast.type === 'error'" class="h-10 w-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                        <div x-show="toast.type === 'info'" class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <p class="text-sm font-black text-slate-900" x-text="toast.title"></p>
                        <p class="mt-1 text-sm text-slate-500 font-medium leading-relaxed" x-text="toast.message"></p>
                    </div>
                    <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                </div>
                <div class="h-1 w-full bg-slate-100">
                    <div class="h-full transition-all duration-[3000ms] ease-linear w-0" :class="{'bg-emerald-500': toast.type === 'success', 'bg-red-500': toast.type === 'error', 'bg-blue-500': toast.type === 'info'}" :style="toast.show ? 'width: 100%' : 'width: 0%'"></div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Staff Management</h1>
                    <p class="text-slate-500 font-medium mt-2">Manage your team, schedules, and ID cards.</p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                        <div class="bg-indigo-50 p-2 rounded-xl text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
                            <p class="text-xl font-black text-slate-800">{{ $totalStaff }}</p>
                        </div>
                    </div>
                    <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                        <div class="bg-emerald-50 p-2 rounded-xl text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active</p>
                            <p class="text-xl font-black text-slate-800">{{ $workingToday }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-200 mb-8">
                <form method="GET" action="{{ route('staff.index') }}" class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative group flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID, phone..." 
                               class="block w-full pl-10 pr-3 py-3 border-none rounded-xl bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-medium">
                    </div>

                    <div class="w-full md:w-48">
                        <div class="relative">
                            <select name="role" onchange="this.form.submit()" class="block w-full pl-4 pr-10 py-3 border-none rounded-xl bg-slate-50 text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer appearance-none">
                                <option value="all">All Roles</option>
                                <option value="sale" {{ request('role') == 'sale' ? 'selected' : '' }}>Sale Staff</option>
                                <option value="stock" {{ request('role') == 'stock' ? 'selected' : '' }}>Stock Manager</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto px-2">
                        <div class="flex bg-slate-100 p-1 rounded-xl">
                            <button type="button" @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="p-2 rounded-lg transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"></path></svg></button>
                            <button type="button" @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="p-2 rounded-lg transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                        </div>
                        <button type="button" @click="openModal()" class="flex-1 md:flex-none bg-slate-900 hover:bg-black text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-slate-300/50 flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>Add Member</span>
                        </button>
                    </div>
                </form>
            </div>

            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                @forelse($staff as $user)
                @php $isWorking = ($user->week_schedule[strtolower(date('D'))] ?? 'Off') !== 'Off'; @endphp
                <div class="group bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 relative">
                    <div class="absolute top-6 left-6 z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $isWorking ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-100' }}">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isWorking ? 'bg-emerald-400 opacity-75' : 'hidden' }}"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 {{ $isWorking ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            </span>
                            {{ $isWorking ? 'Active' : 'Offline' }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center mt-4">
                        <div class="relative group-hover:-translate-y-2 transition-transform duration-300">
                            <div class="w-28 h-28 rounded-full p-1 bg-gradient-to-tr from-indigo-500 to-purple-500">
                                <img src="{{ asset($user->photo_url) }}" class="w-full h-full rounded-full object-cover border-4 border-white">
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <h3 class="text-lg font-bold text-slate-900 font-khmer">{{ $user->name }}</h3>
                            <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mt-1">{{ $user->role }}</p>
                        </div>
                        
                        <div class="flex gap-4 mt-6 w-full justify-center">
                            <div class="text-center px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-[10px] text-slate-400 uppercase font-bold">ID Number</p>
                                <p class="text-xs font-mono font-bold text-slate-700">{{ substr($user->national_id, -3) }}</p>
                            </div>
                            <div class="text-center px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Schedule</p>
                                <p class="text-xs font-bold text-slate-700">{{ ucfirst($user->week_schedule[strtolower(date('D'))] ?? 'Off') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mt-6">
                        <button @click="regenerateCard('{{ $user->national_id }}', '{{ addslashes($user->name) }}', '{{ $user->role }}', '{{ asset($user->photo_url) }}')" 
                                class="col-span-1 flex flex-col items-center justify-center p-3 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all group/btn">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            <span class="text-[10px] font-bold uppercase">ID Card</span>
                        </button>
                        <button @click="editStaff({{ json_encode($user) }})" 
                                class="col-span-1 flex flex-col items-center justify-center p-3 rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-900 hover:text-white transition-all">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span class="text-[10px] font-bold uppercase">Edit</span>
                        </button>
                        <button @click="confirmDelete('{{ route('staff.destroy', $user->id) }}')" 
                                class="col-span-1 flex flex-col items-center justify-center p-3 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span class="text-[10px] font-bold uppercase">Delete</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    <svg class="w-16 h-16 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-lg font-bold">No staff members found.</p>
                    <p class="text-sm">Try adjusting your search filters.</p>
                </div>
                @endforelse
            </div>

            <div x-show="viewMode === 'list'" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden" x-cloak>
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Employee</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Contact</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Schedule Today</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($staff as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($user->photo_url) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-800 font-khmer">{{ $user->name }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-bold uppercase bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded">{{ $user->role }}</span>
                                            <span class="text-[10px] font-mono text-slate-400">{{ $user->national_id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-600">{{ $user->phone }}</div>
                                <div class="text-xs text-slate-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php $sch = $user->week_schedule[strtolower(date('D'))] ?? 'Off'; @endphp
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $sch !== 'Off' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                    {{ ucfirst($sch) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button @click="regenerateCard('{{ $user->national_id }}', '{{ addslashes($user->name) }}', '{{ $user->role }}', '{{ asset($user->photo_url) }}')" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></button>
                                <button @click="editStaff({{ json_encode($user) }})" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <button @click="confirmDelete('{{ route('staff.destroy', $user->id) }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-8 text-slate-400">No staff found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $staff->links() }}
            </div>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                    
                    <div class="bg-slate-50/50 border-b border-slate-100 px-8 py-6 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-black text-slate-900" x-text="isEdit ? 'Edit Staff Profile' : 'Add New Staff'"></h2>
                            <p class="text-sm text-slate-500 mt-1">Manage personal details and weekly schedule.</p>
                        </div>
                        <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 bg-white p-2 rounded-full shadow-sm hover:shadow-md transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    <form :action="formAction" method="POST" enctype="multipart/form-data" class="px-8 py-8 custom-scroll max-h-[80vh] overflow-y-auto">
                        @csrf <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                        
                        <div class="flex flex-col sm:flex-row gap-8 mb-8">
                            <div class="flex-shrink-0 flex flex-col items-center">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Profile Photo</label>
                                <div class="relative group cursor-pointer w-32 h-32">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-slate-100 border-4 border-white shadow-lg group-hover:border-indigo-100 transition-all">
                                        <img :src="photoPreview || (isEdit && formData.photo_url ? formData.photo_url : 'https://ui-avatars.com/api/?name=New+User&background=eff6ff&color=4f46e5')" class="w-full h-full object-cover">
                                    </div>
                                    <div class="absolute inset-0 rounded-full bg-slate-900/0 group-hover:bg-slate-900/30 flex items-center justify-center transition-all">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <input type="file" name="photo" class="absolute inset-0 opacity-0 cursor-pointer" @change="updatePreview($event)" :required="!isEdit">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-2 text-center">Click to upload<br>Max 2MB</p>
                            </div>

                            <div class="flex-1 space-y-5">
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1">Full Name</label>
                                        <input type="text" name="name" x-model="formData.name" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-bold font-khmer px-4 py-2.5 transition-all" placeholder="e.g. Sokha Chan" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1">Role</label>
                                        <select name="role" x-model="formData.role" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 px-4 py-2.5 font-bold transition-all">
                                            <option value="sale">Sale Staff</option>
                                            <option value="stock">Stock Manager</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1">Birth Date</label>
                                        <input type="date" name="dob" x-model="formData.dob" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 px-4 py-2.5 transition-all" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1">Phone Number</label>
                                    <input type="tel" name="phone" x-model="formData.phone" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 px-4 py-2.5 transition-all font-mono" placeholder="012 345 678" required>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5 mb-8">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1">Address</label>
                                <input type="text" name="address" x-model="formData.address" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 px-4 py-2.5 transition-all" required>
                            </div>
                            
                            <div x-show="!isEdit" class="p-5 bg-indigo-50 rounded-2xl border border-indigo-100">
                                <h3 class="text-xs font-black uppercase tracking-widest text-indigo-900 mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Login Credentials
                                </h3>
                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-indigo-400 uppercase mb-1.5">Email</label>
                                        <input type="email" name="email" x-model="formData.email" class="w-full rounded-xl border-indigo-200 bg-white focus:ring-2 focus:ring-indigo-500 px-4 py-2.5" placeholder="staff@pos.com">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-indigo-400 uppercase mb-1.5">Password</label>
                                        <input type="password" name="password" class="w-full rounded-xl border-indigo-200 bg-white focus:ring-2 focus:ring-indigo-500 px-4 py-2.5">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-3 ml-1">Weekly Schedule</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                                <template x-for="day in ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']">
                                    <div class="bg-slate-50 rounded-xl p-2 border border-slate-100 hover:border-indigo-300 transition-colors">
                                        <p class="text-[10px] font-black text-slate-400 uppercase text-center mb-2" x-text="day"></p>
                                        <select :name="'schedule[' + day + ']'" x-model="formData.schedule[day]" 
                                                class="w-full text-xs font-bold rounded-lg border-slate-200 bg-white py-1 pl-2 pr-6 focus:ring-indigo-500 cursor-pointer"
                                                :class="formData.schedule[day] !== 'Off' ? 'text-emerald-600' : 'text-slate-400'">
                                            <option value="Off">Off</option>
                                            <option value="Full Time">Full</option>
                                            <option value="Morning">AM</option>
                                            <option value="Evening">PM</option>
                                        </select>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" @click="showModal = false" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                            <button type="submit" class="px-8 py-3 rounded-xl bg-slate-900 text-white font-bold shadow-xl shadow-slate-300/50 hover:bg-black hover:-translate-y-0.5 transition-all">
                                <span x-text="isEdit ? 'Save Changes' : 'Create Staff'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="deleteModal.show" class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;" x-cloak>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="deleteModal.show = false"></div>
            <div class="bg-white rounded-[2rem] p-8 max-w-sm w-full relative z-10 text-center shadow-2xl animate__animated animate__zoomIn animate__faster">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Delete Staff Member?</h3>
                <p class="text-sm text-slate-500 mb-8 font-medium">This action cannot be undone.</p>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="deleteModal.show = false" class="bg-slate-100 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-200">Cancel</button>
                    <form :action="deleteModal.url" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 shadow-lg shadow-red-500/30">Delete</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div id="id-card-container" style="position: absolute; left: -9999px; top: 0;">
        <div id="id-card" class="w-[360px] h-[640px] bg-[#020408] relative overflow-hidden font-inter flex flex-col items-center">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#3730a3 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="absolute top-[-20%] left-[-20%] w-[350px] h-[350px] bg-blue-600/30 blur-[100px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[350px] h-[350px] bg-purple-600/20 blur-[100px] rounded-full"></div>

            <div class="w-full px-8 pt-8 pb-4 flex justify-between items-start relative z-10">
                <div class="w-14 h-10 bg-gradient-to-br from-yellow-200 via-yellow-500 to-yellow-700 rounded-lg shadow-lg border border-yellow-400/50 flex items-center justify-center overflow-hidden relative">
                    <div class="absolute w-full h-[1px] bg-black/20 top-1/2"></div>
                    <div class="absolute h-full w-[1px] bg-black/20 left-1/3"></div>
                    <div class="absolute h-full w-[1px] bg-black/20 right-1/3"></div>
                </div>
                <div class="text-right">
                    <h1 class="text-white font-black text-xl tracking-widest italic">POS CORP</h1>
                    <p class="text-white/50 text-[8px] uppercase tracking-[0.2em]">Authorized Personnel</p>
                </div>
            </div>

            <div class="relative z-10 mt-6">
                <div class="w-44 h-44 rounded-full p-[2px] bg-gradient-to-b from-blue-400 via-purple-500 to-transparent shadow-[0_0_40px_rgba(59,130,246,0.3)]">
                    <div class="w-full h-full rounded-full bg-[#020408] p-1.5">
                        <img id="card-photo" src="" class="w-full h-full rounded-full object-cover">
                    </div>
                </div>
                <div class="absolute bottom-2 right-4 bg-[#020408] rounded-full p-1">
                    <div class="w-6 h-6 bg-emerald-500 rounded-full border-[3px] border-[#020408] shadow-lg"></div>
                </div>
            </div>

            <div class="w-full text-center mt-6 px-4 z-10">
                <h2 id="card-name" class="text-3xl font-bold text-white font-khmer leading-relaxed drop-shadow-md"></h2>
                <div class="mt-3 flex justify-center">
                    <div class="bg-white/10 backdrop-blur-md border border-white/10 px-6 py-1.5 rounded-full shadow-lg">
                        <span id="card-role" class="text-blue-300 text-xs font-bold uppercase tracking-[0.25em]"></span>
                    </div>
                </div>
            </div>

            <div class="mt-auto w-full bg-white/5 backdrop-blur-xl border-t border-white/10 p-6 flex items-center justify-between z-10">
                <div class="text-left">
                    <p class="text-white/40 text-[9px] uppercase tracking-widest mb-1">Employee ID</p>
                    <p id="card-id" class="text-xl font-mono font-bold text-white tracking-wider"></p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow-lg">
                    <div id="card-qrcode"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function staffManager() {
            return {
                viewMode: 'grid',
                showModal: false,
                isEdit: false,
                photoPreview: null,
                formAction: '{{ route("staff.store") }}',
                formData: { name: '', role: 'sale', schedule: { mon: 'Full Time', tue: 'Full Time', wed: 'Full Time', thu: 'Full Time', fri: 'Full Time', sat: 'Full Time', sun: 'Off' } },
                deleteModal: { show: false, url: '' },
                toast: { show: false, type: 'success', title: '', message: '', timeout: null },

                initSession(success, hasError, errorMsg, newStaff) {
                    if (success) this.triggerToast('success', 'Success', success);
                    if (hasError) this.triggerToast('error', 'Error', errorMsg);
                    if (newStaff) {
                        setTimeout(() => {
                            this.generateCardImage(newStaff.national_id, newStaff.name, newStaff.role, "{{ asset('') }}" + newStaff.photo_url.substring(1));
                        }, 500);
                    }
                },

                triggerToast(type, title, message) {
                    this.toast.type = type;
                    this.toast.title = title;
                    this.toast.message = message;
                    this.toast.show = true;
                    if (this.toast.timeout) clearTimeout(this.toast.timeout);
                    this.toast.timeout = setTimeout(() => { this.toast.show = false; }, 3000);
                },

                openModal() {
                    this.isEdit = false;
                    this.photoPreview = null;
                    this.formData = { name: '', role: 'sale', schedule: { mon: 'Full Time', tue: 'Full Time', wed: 'Full Time', thu: 'Full Time', fri: 'Full Time', sat: 'Full Time', sun: 'Off' } };
                    this.formAction = '{{ route("staff.store") }}';
                    this.showModal = true;
                },

                editStaff(user) {
                    this.isEdit = true;
                    this.photoPreview = null;
                    this.formData = { ...user, schedule: user.week_schedule || {} };
                    ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'].forEach(day => { 
                        if(!this.formData.schedule[day]) this.formData.schedule[day] = 'Off'; 
                    });
                    this.formAction = `/staff/${user.id}`;
                    this.showModal = true;
                },

                updatePreview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.photoPreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                },

                confirmDelete(url) {
                    this.deleteModal.url = url;
                    this.deleteModal.show = true;
                },

                regenerateCard(id, name, role, photoUrl) { 
                    this.generateCardImage(id, name, role, photoUrl); 
                },

                async generateCardImage(id, name, role, photoUrl) {
                    console.log("1. Starting Generation...");
                    this.triggerToast('info', 'Processing', 'Generating High-Res ID Card...');

                    // 1. Update Text Data
                    document.getElementById('card-name').innerText = name;
                    document.getElementById('card-role').innerText = role;
                    document.getElementById('card-id').innerText = id;

                    // 2. Generate QR Code
                    const qrContainer = document.getElementById('card-qrcode');
                    qrContainer.innerHTML = '';
                    try {
                        new QRCode(qrContainer, {
                            text: id,
                            width: 70, height: 70,
                            colorDark : "#0f172a",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    } catch(e) { console.error("QR Code Error", e); }

                    // 3. Handle Image Loading (The Fix)
                    const img = document.getElementById('card-photo');
                    // Reset image source initially
                    img.removeAttribute('src'); 
                    
                    // Fallback Avatar Generator
                    const fallbackUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0D8ABC&color=fff&size=128`;

                    try {
                        // CRITICAL FIX: Convert Absolute URL to Relative URL
                        // This makes the request "Same-Origin" and often bypasses Ngrok checks
                        let fetchUrl = photoUrl;
                        try {
                            const urlObj = new URL(photoUrl);
                            fetchUrl = urlObj.pathname; // e.g., "/storage/staff_photos/image.jpg"
                        } catch(e) {
                            // If URL parsing fails, stick with original
                        }

                        // Add cache buster
                        fetchUrl += '?t=' + new Date().getTime();

                        console.log("Fetching relative URL:", fetchUrl);

                        const response = await fetch(fetchUrl, {
                            headers: { 
                                'ngrok-skip-browser-warning': 'true',
                                'Cache-Control': 'no-cache'
                            }
                        });

                        if (!response.ok) throw new Error('Network error: ' + response.status);
                        
                        // Security Check: Did Ngrok send us HTML instead of an image?
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('text/html')) {
                            throw new Error('Ngrok HTML Warning Page detected');
                        }

                        const blob = await response.blob();
                        
                        // Convert Blob to Base64 (The safest way for html2canvas)
                        const base64 = await new Promise((resolve) => {
                            const reader = new FileReader();
                            reader.onloadend = () => resolve(reader.result);
                            reader.readAsDataURL(blob);
                        });

                        img.src = base64;

                    } catch (error) {
                        console.warn("Image fetch failed, using fallback. Reason:", error);
                        img.src = fallbackUrl;
                        this.triggerToast('info', 'Note', 'Using avatar due to network restrictions.');
                    }

                    // 4. Wait for Image to Load in DOM
                    await new Promise(resolve => {
                        if (img.complete) return resolve();
                        img.onload = resolve;
                        img.onerror = resolve; // Proceed even if it fails
                    });

                    // 5. Wait for Fonts
                    await document.fonts.ready;

                    // 6. Capture
                    console.log("2. Assets Ready, Capturing...");
                    
                    setTimeout(() => {
                        const element = document.querySelector("#id-card");
                        
                        html2canvas(element, {
                            scale: 3,
                            useCORS: true, 
                            allowTaint: true,
                            backgroundColor: null,
                            logging: false 
                        }).then(canvas => {
                            console.log("3. Capture Complete");
                            const link = document.createElement('a');
                            link.download = `ID_${name.replace(/\s+/g, '_')}_${id}.png`;
                            link.href = canvas.toDataURL("image/png");
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            this.triggerToast('success', 'Done!', 'ID Card Downloaded.');
                        }).catch(err => {
                            console.error("Canvas Error:", err);
                            alert("Error generating image: " + err.message);
                            this.triggerToast('error', 'Error', 'Could not save image.');
                        });
                    }, 500); // 500ms delay to ensure rendering is stable
                }
            }
        }
    </script>
</x-app-layout>