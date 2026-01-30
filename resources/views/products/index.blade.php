<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        
        /* Progress Bar Animation */
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .animate-shrink { animation-name: shrink; animation-timing-function: linear; animation-fill-mode: forwards; }
    </style>

    <div class="min-h-screen" x-data="productList()" x-init="initList()">

        <div x-show="deleteModalOpen" style="display: none;" 
             class="fixed inset-0 z-[999] w-screen h-screen flex items-center justify-center px-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="deleteModalOpen = false"></div>

            <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 text-center relative overflow-hidden transform transition-all scale-100 z-10"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500 shadow-inner ring-4 ring-red-50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>

                <h3 class="text-2xl font-black text-slate-800 mb-2">Delete Product?</h3>
                <p class="text-slate-500 text-sm mb-8 leading-relaxed font-medium">
                    Are you sure you want to remove this item? <br>This action cannot be undone.
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <button @click="deleteModalOpen = false" class="py-3.5 rounded-xl font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 hover:text-slate-700 transition-all">
                        Cancel
                    </button>
                    <button @click="submitDelete()" class="py-3.5 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span>Yes, Delete</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
            <template x-for="note in notifications" :key="note.id">
                <div x-show="true"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-x-4 scale-95"
                     class="pointer-events-auto w-full overflow-hidden rounded-2xl shadow-[0_20px_40px_rgba(0,0,0,0.08)] bg-white border border-slate-100 relative group cursor-pointer"
                     @click="removeNotify(note.id)">
                    
                    <div class="p-4 flex items-start gap-4">
                        <div class="shrink-0">
                            <template x-if="note.type === 'success'">
                                <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center ring-4 ring-emerald-50/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </template>
                            <template x-if="note.type === 'error'">
                                <div class="h-10 w-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center ring-4 ring-rose-50/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </div>
                            </template>
                        </div>

                        <div class="flex-1 pt-0.5">
                            <p class="text-sm font-black text-slate-800" x-text="note.title"></p>
                            <p class="mt-0.5 text-sm text-slate-500 font-medium leading-relaxed" x-text="note.message"></p>
                        </div>

                        <button class="text-slate-300 hover:text-slate-500 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>

                    <div class="h-1 w-full bg-slate-50 absolute bottom-0 left-0">
                        <div class="h-full w-full origin-left animate-shrink"
                             :style="{ animationDuration: '4000ms' }"
                             :class="{
                                'bg-emerald-500': note.type === 'success',
                                'bg-rose-500': note.type === 'error'
                             }"></div>
                    </div>
                </div>
            </template>
        </div>

        <form x-ref="deleteForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10">
                <div>
                    <h2 class="text-4xl font-black text-slate-800 tracking-tighter">Inventory</h2>
                    <p class="text-slate-500 font-medium mt-1 ml-1">Manage catalog, pricing & stock.</p>
                </div>
                
                @if(in_array(auth()->user()->role, ['admin', 'stock']))
                <a href="{{ route('products.create') }}" class="group flex items-center gap-3 bg-slate-900 hover:bg-black text-white px-6 py-3 rounded-2xl font-bold shadow-xl shadow-slate-900/20 transition-all transform hover:-translate-y-1">
                    <span class="bg-white/20 p-1 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></span>
                    <span>Add Product</span>
                </a>
                @endif
            </div>

            <div class="bg-white rounded-[2.5rem] p-5 shadow-lg shadow-slate-200/50 border border-white mb-8 sticky top-4 z-10">
                <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto flex-1">
                        <div class="relative w-full md:w-96 group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                                   class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none focus:ring-4 focus:ring-indigo-500/20 rounded-2xl font-bold text-slate-700 transition-all placeholder:text-slate-400/80"
                                   onchange="this.form.submit()">
                        </div>

                        <div class="relative w-full md:w-56 group">
                            <select name="category" onchange="this.form.submit()" class="w-full pl-5 pr-10 py-3.5 bg-slate-50 border-none focus:ring-4 focus:ring-indigo-500/20 rounded-2xl font-bold text-slate-600 appearance-none cursor-pointer hover:bg-slate-100 transition-colors">
                                <option value="all">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                        </div>
                    </div>

                    <div class="flex bg-slate-100 p-1.5 rounded-2xl">
                        <button type="button" @click="viewMode = 'grid'" 
                                :class="viewMode === 'grid' ? 'bg-white text-indigo-600 shadow-md transform scale-105' : 'text-slate-400 hover:text-slate-600'"
                                class="p-2.5 rounded-xl transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"></path></svg>
                        </button>
                        <button type="button" @click="viewMode = 'list'" 
                                :class="viewMode === 'list' ? 'bg-white text-indigo-600 shadow-md transform scale-105' : 'text-slate-400 hover:text-slate-600'"
                                class="p-2.5 rounded-xl transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="bg-white rounded-[2.5rem] p-16 text-center border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-500 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800">No products found</h3>
                    <p class="text-slate-500 mt-2 font-medium">Try adjusting your search or filters.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-6 px-6 py-2.5 bg-white border-2 border-slate-200 rounded-xl text-slate-600 font-bold hover:border-indigo-500 hover:text-indigo-600 transition-all">Clear Filters</a>
                </div>
            @else

                <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate__animated animate__fadeIn">
                    @foreach($products as $product)
                    <div class="bg-white rounded-[2rem] p-3 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-white hover:border-indigo-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        
                        <div class="h-52 w-full bg-slate-100 rounded-[1.5rem] relative overflow-hidden">
                            @if($product->image_url)
                                <img src="{{ asset($product->image_url) }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-slate-300 bg-slate-50">
                                    <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-lg bg-white/90 backdrop-blur text-slate-800 shadow-sm">
                                    {{ $product->category->name ?? 'None' }}
                                </span>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-lg shadow-sm 
                                    {{ $product->qty < 5 ? 'bg-red-500/90 text-white' : 'bg-emerald-500/90 text-white' }}">
                                    {{ $product->qty }} Left
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-bold text-slate-800 text-lg mb-1 truncate">{{ $product->name }}</h3>
                            <p class="text-xs font-mono text-slate-400 mb-4 bg-slate-50 inline-block px-2 py-0.5 rounded">{{ $product->barcode }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->sale_price, 2) }}</span>
                                
                                @if(in_array(auth()->user()->role, ['admin', 'stock']))
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-100 hover:scale-105 transition-all shadow-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <button type="button" @click="openDeleteModal('{{ route('products.destroy', $product->id) }}')" 
                                            class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 hover:scale-105 transition-all shadow-sm" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div x-show="viewMode === 'list'" class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden animate__animated animate__fadeIn">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4">Product</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">Price</th>
                                    <th class="px-6 py-4">Stock</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($products as $product)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-100">
                                                @if($product->image_url)
                                                    <img src="{{ asset($product->image_url) }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800">{{ $product->name }}</p>
                                                <p class="text-[10px] font-mono text-slate-400">{{ $product->barcode }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-slate-100 text-slate-600 uppercase tracking-wide">
                                            {{ $product->category->name ?? 'None' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-900">${{ number_format($product->sale_price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $product->qty < 5 ? 'bg-red-500 animate-pulse' : 'bg-emerald-500' }}"></div>
                                            <span class="text-sm font-bold {{ $product->qty < 5 ? 'text-red-600' : 'text-slate-600' }}">{{ $product->qty }} Units</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(in_array(auth()->user()->role, ['admin', 'stock']))
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('products.edit', $product->id) }}" class="p-2 bg-white border border-slate-200 text-amber-600 rounded-lg hover:bg-amber-50 hover:border-amber-200 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <button type="button" @click="openDeleteModal('{{ route('products.destroy', $product->id) }}')" 
                                                    class="p-2 bg-white border border-slate-200 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-200 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <script>
        function productList() {
            return {
                viewMode: localStorage.getItem('viewMode') || 'grid',
                notifications: [],
                deleteModalOpen: false,
                deleteTargetUrl: '',

                initList() {
                    this.$watch('viewMode', val => localStorage.setItem('viewMode', val));

                    const successMsg = @json(session('success'));
                    const errorMsg = @json(session('error'));

                    if (successMsg) this.notify('Success!', successMsg, 'success');
                    if (errorMsg) this.notify('Error', errorMsg, 'error');
                },

                notify(title, message, type = 'success') {
                    const id = Date.now();
                    this.notifications.push({ id, title, message, type });
                    setTimeout(() => { this.removeNotify(id) }, 4000);
                },

                removeNotify(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },

                openDeleteModal(url) {
                    this.deleteTargetUrl = url;
                    this.deleteModalOpen = true;
                },

                submitDelete() {
                    this.$refs.deleteForm.action = this.deleteTargetUrl;
                    this.$refs.deleteForm.submit();
                }
            }
        }
    </script>
</x-app-layout>