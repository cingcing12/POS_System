<x-app-layout>
    <div x-data="categoryManager()" 
         x-init="initSession('{{ session('success') }}', '{{ session('error') }}', {{ $errors->any() ? 'true' : 'false' }}, '{{ $errors->first() }}')" 
         class="min-h-screen bg-[#F8FAFC] py-10 relative font-[Outfit]">

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
                    </div>
                    <div class="flex-1 pt-0.5">
                        <p class="text-sm font-black text-slate-900" x-text="toast.title"></p>
                        <p class="mt-1 text-sm text-slate-500 font-medium leading-relaxed" x-text="toast.message"></p>
                    </div>
                    <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                </div>
                <div class="h-1 w-full bg-slate-100">
                    <div class="h-full transition-all duration-[3000ms] ease-linear w-0" :class="{'bg-emerald-500': toast.type === 'success', 'bg-red-500': toast.type === 'error'}" :style="toast.show ? 'width: 100%' : 'width: 0%'"></div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Categories</h1>
                    <p class="text-slate-500 font-medium mt-2">Organize your inventory for better management.</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-slate-200">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ $categories->count() }} Active Categories</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="lg:col-span-1 sticky top-8">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden transition-all duration-300"
                         :class="isEditing ? 'ring-2 ring-amber-400' : 'ring-0'">
                        
                        <div class="absolute top-0 left-0 w-full h-1.5" :class="isEditing ? 'bg-gradient-to-r from-amber-400 to-orange-500' : 'bg-gradient-to-r from-indigo-500 to-purple-600'"></div>

                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-black text-slate-900" x-text="isEditing ? 'Edit Category' : 'New Category'"></h3>
                            <span x-show="isEditing" class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider animate-pulse">Editing Mode</span>
                        </div>
                        
                        <form :action="formAction" method="POST" @submit.prevent="submitForm">
                            @csrf
                            <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Category Name</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        </div>
                                        <input type="text" name="name" x-model="formName" 
                                               class="w-full bg-slate-50 border-none rounded-xl pl-12 pr-4 py-3.5 font-bold text-slate-800 focus:ring-2 focus:bg-white transition-all placeholder:text-slate-400"
                                               :class="isEditing ? 'focus:ring-amber-500' : 'focus:ring-indigo-500'"
                                               placeholder="e.g. Beverages" required>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button type="submit" 
                                            class="flex-1 py-3.5 rounded-xl font-bold text-white shadow-lg transition-all transform active:scale-95 flex items-center justify-center gap-2"
                                            :class="isEditing ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/30' : 'bg-slate-900 hover:bg-black shadow-slate-900/30'">
                                        <span x-text="isEditing ? 'Update' : 'Create'"></span>
                                        <svg x-show="!isEditing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <svg x-show="isEditing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>

                                    <button type="button" x-show="isEditing" @click="resetForm()" 
                                            class="px-4 py-3.5 bg-slate-100 text-slate-500 rounded-xl font-bold hover:bg-slate-200 transition-colors"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    @if($categories->isEmpty())
                        <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200 flex flex-col items-center justify-center h-full min-h-[300px]">
                            <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-3xl flex items-center justify-center mb-6 animate-pulse">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mb-2">No Categories Yet</h3>
                            <p class="text-slate-500 max-w-xs mx-auto">Create your first category to start organizing your product inventory efficiently.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($categories as $category)
                            <div class="group bg-white rounded-[2rem] p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 relative overflow-hidden">
                                
                                <div class="flex items-center justify-between mb-4 relative z-10">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-lg font-black shadow-lg shadow-indigo-500/20">
                                        {{ substr($category->name, 0, 1) }}
                                    </div>
                                    
                                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                                        <button @click="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                                                class="p-2 bg-amber-50 text-amber-500 rounded-xl hover:bg-amber-500 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button @click="confirmDelete('{{ route('categories.destroy', $category->id) }}')" 
                                                class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-slate-800 mb-1 relative z-10">{{ $category->name }}</h3>
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider relative z-10">{{ $category->products_count ?? 0 }} Products Linked</p>

                                <div class="absolute -bottom-6 -right-6 text-slate-50 transform rotate-12 group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div x-show="deleteModal.show" class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;" x-cloak>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="deleteModal.show = false"></div>
            <div class="bg-white rounded-[2rem] p-8 max-w-sm w-full relative z-10 text-center shadow-2xl animate__animated animate__zoomIn animate__faster">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Delete Category?</h3>
                <p class="text-sm text-slate-500 mb-8 font-medium">Products in this category may become uncategorized.</p>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="deleteModal.show = false" class="bg-slate-100 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-200 transition-colors">Cancel</button>
                    <form :action="deleteModal.url" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 shadow-lg shadow-red-500/30 transition-colors">Delete</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function categoryManager() {
            return {
                isEditing: false,
                formName: '',
                formAction: '{{ route("categories.store") }}',
                deleteModal: { show: false, url: '' },
                toast: { show: false, type: 'success', title: '', message: '', timeout: null },

                initSession(success, error, hasErrors, firstError) {
                    if (success) this.triggerToast('success', 'Success', success);
                    if (error) this.triggerToast('error', 'Error', error);
                    if (hasErrors) this.triggerToast('error', 'Validation Error', firstError);
                },

                triggerToast(type, title, message) {
                    this.toast.type = type;
                    this.toast.title = title;
                    this.toast.message = message;
                    this.toast.show = true;
                    if (this.toast.timeout) clearTimeout(this.toast.timeout);
                    this.toast.timeout = setTimeout(() => { this.toast.show = false; }, 3000);
                },

                editCategory(id, name) {
                    this.isEditing = true;
                    this.formName = name;
                    this.formAction = `/categories/${id}`;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                resetForm() {
                    this.isEditing = false;
                    this.formName = '';
                    this.formAction = '{{ route("categories.store") }}';
                },

                confirmDelete(url) {
                    this.deleteModal.url = url;
                    this.deleteModal.show = true;
                },

                submitForm(event) {
                    event.target.submit();
                }
            }
        }
    </script>
</x-app-layout>