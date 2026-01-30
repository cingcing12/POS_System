<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        [x-cloak] { display: none !important; }
        
        /* Progress Bar Animation */
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .animate-shrink { animation-name: shrink; animation-timing-function: linear; animation-fill-mode: forwards; }
    </style>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="productForm()">
        
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
                            <template x-if="note.type === 'info'">
                                <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center ring-4 ring-blue-50/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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
                                'bg-rose-500': note.type === 'error',
                                'bg-blue-500': note.type === 'info'
                             }"></div>
                    </div>
                </div>
            </template>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
                <div>
                    <h1 class="text-4xl font-black text-slate-800 tracking-tighter flex items-center gap-3">
                        <span class="p-3 bg-slate-900 rounded-2xl text-white shadow-xl shadow-slate-900/20">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </span>
                        New Product
                    </h1>
                    <p class="mt-2 text-slate-500 font-medium ml-1">Add items to your inventory seamlessly.</p>
                </div>
                <a href="{{ route('products.index') }}" class="group flex items-center gap-2 px-6 py-3 bg-white text-slate-600 font-bold rounded-2xl shadow-sm border border-slate-200 hover:border-indigo-200 hover:text-indigo-600 transition-all hover:shadow-md">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Inventory
                </a>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="lg:col-span-2 space-y-8">
                        
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden group">
                            <h3 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                                <span class="bg-indigo-100 p-2 rounded-lg text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></span>
                                Basic Information
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 pl-1">Product Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" x-model="name" class="w-full bg-slate-50 border-none focus:ring-4 focus:ring-indigo-500/20 rounded-2xl px-5 py-4 font-bold text-slate-800 transition-all placeholder-slate-300 shadow-inner" placeholder="e.g. Signature Iced Coffee" required>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 pl-1">Category <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select name="category_id" class="w-full appearance-none bg-slate-50 border-none focus:ring-4 focus:ring-indigo-500/20 rounded-2xl px-5 py-4 font-bold text-slate-600 shadow-inner" required>
                                                <option value="">Select Category...</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 pl-1">Barcode</label>
                                        <div class="relative flex items-center">
                                            <input type="text" name="barcode" x-model="barcode" class="w-full bg-slate-50 border-none focus:ring-4 focus:ring-indigo-500/20 rounded-2xl px-5 py-4 font-mono font-bold text-slate-700 tracking-wider shadow-inner" placeholder="Scan or Auto-Gen">
                                            
                                            <button type="button" @click="generateBarcode()" class="absolute right-3 p-2 bg-white text-indigo-500 rounded-xl hover:bg-indigo-50 border border-slate-200 shadow-sm transition-all" title="Generate Random Barcode">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                            <h3 class="text-lg font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                                <span class="bg-emerald-100 p-2 rounded-lg text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                                Pricing & Stock
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 pl-1">Sale Price <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">$</span>
                                        <input type="number" name="sale_price" step="0.01" x-model="price" class="w-full bg-slate-50 border-none focus:ring-4 focus:ring-emerald-500/20 rounded-2xl pl-8 pr-4 py-4 font-bold text-slate-800 shadow-inner" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 pl-1">Cost Price</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">$</span>
                                        <input type="number" name="cost_price" step="0.01" class="w-full bg-slate-50 border-none focus:ring-4 focus:ring-slate-500/20 rounded-2xl pl-8 pr-4 py-4 font-bold text-slate-600 shadow-inner" placeholder="0.00">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 pl-1">Stock Qty <span class="text-red-500">*</span></label>
                                    <input type="number" name="qty" x-model="qty" class="w-full bg-slate-50 border-none focus:ring-4 focus:ring-indigo-500/20 rounded-2xl px-5 py-4 font-bold text-slate-800 shadow-inner" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-8 sticky top-8">
                        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2.5rem] p-6 text-white shadow-2xl">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Mobile Preview
                            </h3>
                            
                            <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-slate-200/20 transform transition-all hover:scale-105 duration-300">
                                <div class="h-48 w-full bg-gray-100 relative overflow-hidden group">
                                    <template x-if="imagePreview">
                                        <img :src="imagePreview" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    </template>
                                    <template x-if="!imagePreview">
                                        <div class="h-full w-full flex flex-col items-center justify-center text-gray-300 bg-slate-100">
                                            <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[10px] font-bold uppercase tracking-wide opacity-50">Upload Image</span>
                                        </div>
                                    </template>
                                </div>
                                <div class="p-4 border-b border-gray-50">
                                    <h3 class="text-sm font-bold text-gray-800 mb-1 truncate" x-text="name || 'Product Name'"></h3>
                                    <div class="flex justify-between items-end">
                                        <span class="text-lg font-black text-indigo-600" x-text="'$' + (price || '0.00')"></span>
                                        <span class="text-[10px] text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded-md" x-text="barcode || 'NO-BARCODE'"></span>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50 flex justify-center items-center h-20" x-show="barcode">
                                    <svg id="cardBarcode" class="h-full"></svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-white">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 pl-1">Product Image <span class="text-red-500">*</span></label>
                            <label class="group flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/50 transition-all relative overflow-hidden bg-slate-50">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="mb-1 text-sm text-slate-500 font-bold group-hover:text-indigo-600">Click to upload</p>
                                </div>
                                <input type="file" name="image" class="hidden" @change="previewImage" required />
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full py-4 rounded-2xl text-white font-black text-lg shadow-xl shadow-indigo-600/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2"
                                :class="isLoading ? 'bg-slate-800 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'">
                            <span x-show="!isLoading">Save Product</span>
                            <span x-show="isLoading" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <canvas id="downloadCanvas" style="display:none;"></canvas>

    <script>
        function productForm() {
            return {
                name: '{{ old("name") }}',
                price: '{{ old("sale_price") }}',
                qty: '{{ old("qty") }}',
                barcode: '{{ old("barcode") }}',
                imagePreview: null,
                isLoading: false,
                notifications: [], 

                init() {
                    this.$watch('barcode', (val) => {
                        if(val) {
                            try { JsBarcode("#cardBarcode", val, { format: "CODE128", lineColor: "#334155", width: 1.5, height: 40, displayValue: false }); } catch(e) {}
                        } else {
                            document.getElementById('cardBarcode').innerHTML = '';
                        }
                    });
                },

                // --- CUSTOM NOTIFICATIONS ---
                notify(title, message, type = 'success') {
                    const id = Date.now();
                    this.notifications.push({ id, title, message, type });
                    setTimeout(() => { this.removeNotify(id) }, 4000);
                },

                removeNotify(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },

                // --- FORM ACTIONS ---
                generateBarcode() {
                    this.barcode = Math.floor(100000000000 + Math.random() * 900000000000).toString();
                    this.notify('Barcode Generated', 'Random 12-digit code assigned', 'info');
                },

                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) this.imagePreview = URL.createObjectURL(file);
                },

                // --- AJAX SUBMISSION ---
                submitForm(e) {
                    this.isLoading = true;
                    const formData = new FormData(e.target);

                    fetch(e.target.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(async response => {
                        this.isLoading = false;
                        const data = await response.json();

                        if (!response.ok) {
                            if (data.errors) {
                                Object.values(data.errors).flat().forEach(err => this.notify('Validation Error', err, 'error'));
                            } else {
                                this.notify('System Error', data.message || 'Unknown error occurred', 'error');
                            }
                            return;
                        }

                        // Success
                        if (data.success || response.status === 200 || response.status === 201) {
                            this.notify('Product Saved!', 'Item successfully added to inventory', 'success');
                            
                            const code = this.barcode;
                            if(code) this.downloadBarcode(code);

                            // Reset
                            this.name = ''; this.price = ''; this.qty = ''; this.barcode = ''; this.imagePreview = null;
                            e.target.reset();
                        }
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.error(error);
                        this.notify('Controller Error', 'Controller must return JSON, not Redirect.', 'error');
                    });
                },

                downloadBarcode(code) {
                    this.notify('Downloading...', 'Saving barcode image to device', 'info');
                    setTimeout(() => {
                        const canvas = document.getElementById('downloadCanvas');
                        try {
                            JsBarcode(canvas, code, { format: "CODE128", lineColor: "#000", width: 2, height: 100, displayValue: true });
                            const link = document.createElement('a');
                            link.download = `Barcode-${code}.png`;
                            link.href = canvas.toDataURL("image/png");
                            link.click();
                        } catch(e) {}
                    }, 500);
                }
            }
        }
    </script>
</x-app-layout>