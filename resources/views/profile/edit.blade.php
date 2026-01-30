<x-app-layout>
    <div x-data="toastNotification()" 
         x-init="initSession('{{ session('status') }}', '{{ session('success') }}', '{{ session('error') }}')"
         class="min-h-screen bg-[#F3F4F6] py-12 relative">

        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
            <div x-show="show" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-2xl shadow-2xl bg-white border border-slate-100"
                 style="display: none;">
                
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div x-show="type === 'success'" class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div x-show="type === 'error'" class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </div>
                        </div>
                        
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-black text-slate-900" x-text="title"></p>
                            <p class="mt-1 text-sm text-slate-500 font-medium leading-relaxed" x-text="message"></p>
                        </div>
                        
                        <div class="ml-4 flex flex-shrink-0">
                            <button @click="show = false" class="inline-flex rounded-md bg-white text-slate-400 hover:text-slate-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="h-1 w-full bg-slate-50">
                    <div class="h-full transition-all duration-[3000ms] ease-linear w-0" 
                         :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
                         :style="show ? 'width: 100%' : 'width: 0%'"></div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight">Account Settings</h1>
                    <p class="text-slate-500 font-medium mt-2">Manage your personal profile and security preferences.</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-slate-200">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Active Status</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden relative border border-slate-100 group">
                        <div class="h-32 bg-slate-900 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-700 opacity-90"></div>
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                        </div>

                        <div class="px-8 pb-8 -mt-12 text-center relative z-10">
                            <div class="relative inline-block">
                                <div class="w-28 h-28 rounded-full p-1.5 bg-white shadow-lg mx-auto">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-slate-100 relative group-hover:shadow-inner transition-all">
                                        <img id="profile-preview" 
                                             src="{{ $user->photo_url ? asset($user->photo_url) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=eff6ff&color=4f46e5' }}" 
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    
                                        <label for="photo-upload" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer backdrop-blur-[2px]">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </label>
                                    </div>
                                </div>
                                <div class="absolute bottom-1 right-1 bg-white rounded-full p-1 shadow-md">
                                    <div class="w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                                </div>
                            </div>

                            <h2 class="mt-4 text-xl font-black text-slate-900">{{ $user->name }}</h2>
                            <p class="text-sm font-medium text-slate-500">{{ $user->email }}</p>
                            
                            <div class="mt-4 flex justify-center gap-2">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider 
                                    {{ $user->role === 'admin' ? 'bg-red-50 text-red-600 border border-red-100' : '' }}
                                    {{ $user->role === 'sale' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}
                                    {{ $user->role === 'stock' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Contact Details</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400">Phone</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $user->phone ?? 'Not set' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400">Location</p>
                                    <p class="text-sm font-bold text-slate-800 truncate w-40">{{ $user->address ?? 'Not set' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 relative overflow-hidden">
                        @csrf
                        @method('patch')
                        
                        <input type="file" id="photo-upload" name="photo" class="hidden" onchange="document.getElementById('profile-preview').src = window.URL.createObjectURL(this.files[0])">

                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">Personal Information</h3>
                                <p class="text-sm text-slate-500 mt-1">Update your basic profile details.</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-1">
                                <label for="name" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all" required>
                                <x-input-error class="mt-1" :messages="$errors->get('name')" />
                            </div>

                            <div class="space-y-1">
                                <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all" required>
                                <x-input-error class="mt-1" :messages="$errors->get('email')" />
                            </div>

                            <div class="space-y-1">
                                <label for="phone" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 font-bold font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>

                            <div class="space-y-1">
                                <label for="address" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-slate-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-slate-300/50 hover:-translate-y-0.5 transition-all">Save Changes</button>
                        </div>
                    </form>

                    <form method="post" action="{{ route('password.update') }}" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                        @csrf
                        @method('put')

                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">Security</h3>
                                <p class="text-sm text-slate-500 mt-1">Update your password to keep your account safe.</p>
                            </div>
                            <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="current_password" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Current Password</label>
                                <div class="relative mt-1">
                                    <input type="password" name="current_password" id="current_password" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all" autocomplete="current-password">
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">New Password</label>
                                    <input type="password" name="password" id="password" class="w-full mt-1 bg-slate-50 border-none rounded-xl px-4 py-3 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all" autocomplete="new-password">
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="password_confirmation" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full mt-1 bg-slate-50 border-none rounded-xl px-4 py-3 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 transition-all" autocomplete="new-password">
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-slate-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:-translate-y-0.5 transition-all">Update Password</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toastNotification() {
            return {
                show: false,
                type: 'success', // success or error
                title: '',
                message: '',
                timeout: null,
                
                // Initialize based on Laravel Session Keys
                initSession(status, success, error) {
                    if (status === 'profile-updated') {
                        this.trigger('success', 'Profile Updated', 'Your personal information has been saved.');
                    } else if (status === 'password-updated') {
                        this.trigger('success', 'Security Updated', 'Your password has been changed securely.');
                    } else if (success) {
                        this.trigger('success', 'Success', success);
                    } else if (error) {
                        this.trigger('error', 'Error', error);
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
</x-app-layout>