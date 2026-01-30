<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        .toast-enter { animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="productEditForm()">
        
        <div class="fixed top-24 right-6 z-[100] flex flex-col gap-3 pointer-events-none">
            <template x-for="note in notifications" :key="note.id">
                <div class="toast-enter pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl backdrop-blur-md border border-white/50 min-w-[300px] max-w-sm"
                     :class="{
                        'bg-red-500/95 text-white': note.type === 'error',
                        'bg-slate-800/95 text-white': note.type === 'info'
                     }">
                    <div x-html="note.icon"></div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold" x-text="note.title"></h4>
                        <p class="text-xs opacity-90 leading-tight mt-0.5" x-text="note.message"></p>
                    </div>
                    <button @click="removeNotify(note.id)" class="opacity-50 hover:opacity-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            </template>
        </div>

        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-3 bg-amber-500 rounded-2xl text-white shadow-lg shadow-amber-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </span>
                        Edit Product
                    </h1>
                    <p class="mt-2 text-slate-500 font-medium ml-1">Updating: <span class="font-bold text-slate-800">{{ $product->name }}</span></p>
                </div>
                <a href="{{ route('products.index') }}" class="group flex items-center gap-2 px-6 py-3 bg-white text-slate-600 font-bold rounded-xl shadow-sm border border-slate-200 hover:border-amber-200 hover:text-amber-600 transition-all">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Cancel Edit
                </a>
            </div>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" @submit="isLoading = true">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="lg:col-span-2 space-y-8">
                        
                        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Basic Information
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Product Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" x-model="name" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-amber-500 rounded-xl px-4 py-3.5 font-bold text-slate-800 transition-all placeholder-slate-300" required>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select name="category_id" class="w-full appearance-none bg-slate-50 border-none focus:ring-2 focus:ring-amber-500 rounded-xl px-4 py-3.5 font-bold text-slate-600" required>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Barcode</label>
                                        <div class="relative flex items-center">
                                            <input type="text" name="barcode" value="{{ $product->barcode }}" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-amber-500 rounded-xl px-4 py-3.5 font-mono font-bold text-slate-700 tracking-wider">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-green-500"></div>
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pricing & Stock
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 pl-1">Sale Price <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">$</span>
                                        <input type="number" name="sale_price" step="0.01" x-model="price" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-green-500 rounded-xl pl-8 pr-4 py-3.5 font-bold text-slate-800" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 pl-1">Cost Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold">$</span>
                                        <input type="number" name="cost_price" step="0.01" value="{{ $product->cost_price }}" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-slate-500 rounded-xl pl-8 pr-4 py-3.5 font-bold text-slate-600">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 pl-1">Stock Qty <span class="text-red-500">*</span></label>
                                    <input type="number" name="qty" x-model="qty" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-amber-500 rounded-xl px-4 py-3.5 font-bold text-slate-800" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-8 sticky top-8">
                        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2.5rem] p-6 text-white shadow-2xl">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> Preview Changes
                            </h3>
                            
                            <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-slate-200/20">
                                <div class="h-48 w-full bg-gray-100 relative overflow-hidden group">
                                    <template x-if="imagePreview">
                                        <img :src="imagePreview" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    </template>
                                    <template x-if="!imagePreview">
                                        @if($product->image_url)
                                            <img src="{{ asset($product->image_url) }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        @else
                                            <div class="h-full w-full flex flex-col items-center justify-center text-gray-300 bg-slate-100">
                                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </template>
                                </div>
                                <div class="p-4 border-b border-gray-50">
                                    <h3 class="text-sm font-bold text-gray-800 mb-1 truncate" x-text="name || 'Product Name'"></h3>
                                    <div class="flex justify-between items-end">
                                        <span class="text-lg font-black text-indigo-600" x-text="'$' + (price || '0.00')"></span>
                                        <span class="text-[10px] text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded-md">{{ $product->barcode }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-white">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 pl-1">New Image (Optional)</label>
                            <label class="group flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-amber-500 hover:bg-amber-50/50 transition-all relative overflow-hidden bg-slate-50">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="mb-1 text-sm text-slate-500 font-bold group-hover:text-amber-600">Click to change</p>
                                </div>
                                <input type="file" name="image" class="hidden" @change="previewImage" />
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full py-4 rounded-2xl text-white font-black text-lg shadow-xl shadow-amber-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2"
                                :class="isLoading ? 'bg-slate-800 cursor-not-allowed' : 'bg-amber-500 hover:bg-amber-600'">
                            <span x-show="!isLoading">Update Product</span>
                            <span x-show="isLoading" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function productEditForm() {
            return {
                name: '{{ $product->name }}',
                price: '{{ $product->sale_price }}',
                qty: '{{ $product->qty }}',
                imagePreview: null,
                isLoading: false,
                notifications: [],

                init() {
                    // Check for Flash Errors (If update fails and redirects back)
                    const errors = @json($errors->all());
                    const errorMsg = @json(session('error'));

                    if(errors.length > 0) {
                        errors.forEach(e => this.notify('Validation Error', e, 'error'));
                    }
                    if(errorMsg) this.notify('Error', errorMsg, 'error');
                },

                notify(title, message, type = 'success') {
                    const id = Date.now();
                    const icons = {
                        error: `<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                        info: `<svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
                    };
                    this.notifications.push({ id, title, message, type, icon: icons[type] });
                    setTimeout(() => { this.removeNotify(id) }, 4000);
                },

                removeNotify(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },

                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) this.imagePreview = URL.createObjectURL(file);
                }
            }
        }
    </script>
</x-app-layout>