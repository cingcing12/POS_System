<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
        
        /* Animations */
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        .toast-enter { animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }

        /* Camera Styles */
        #reader-remote { width: 100%; height: 100%; border-radius: 1.5rem; overflow: hidden; background: black; position: relative; }
        video { object-fit: cover; width: 100% !important; height: 100% !important; border-radius: 1.5rem; }
    </style>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" x-data="productForm()" x-init="init()">
        
        <div class="fixed top-24 right-6 z-[100] flex flex-col gap-3 pointer-events-none">
            <template x-for="note in notifications" :key="note.id">
                <div class="toast-enter pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl backdrop-blur-md border border-white/50 min-w-[280px]"
                     :class="{
                        'bg-emerald-500/95 text-white': note.type === 'success',
                        'bg-red-500/95 text-white': note.type === 'error',
                        'bg-slate-800/95 text-white': note.type === 'info'
                     }">
                    <div x-html="note.icon"></div>
                    <div>
                        <h4 class="text-sm font-bold" x-text="note.title"></h4>
                        <p class="text-xs opacity-90" x-text="note.message"></p>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="activeModal" style="display: none;" 
             class="fixed inset-0 z-[80] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
             x-transition.opacity>
            
            <div x-show="activeModal === 'connect'" @click.away="activeModal = null" 
                 class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                <h3 class="text-2xl font-black text-slate-800 mb-6">Sync Scanner</h3>
                
                <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 mb-6">
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">TERMINAL ID</p>
                    <div class="text-4xl font-mono font-black text-indigo-600 tracking-wider select-all" x-text="myTerminalId"></div>
                    <p class="text-xs text-indigo-400 mt-2">Enter this code on your mobile device</p>
                </div>

                <div class="relative flex py-2 items-center mb-6">
                    <div class="flex-grow border-t border-gray-100"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-300 text-xs font-bold">OR</span>
                    <div class="flex-grow border-t border-gray-100"></div>
                </div>

                <button @click="openInputModal()" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl hover:bg-black transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Use THIS Device to Scan
                </button>
            </div>

            <div x-show="activeModal === 'input_id'" @click.away="activeModal = null" class="bg-white w-full max-w-sm rounded-[2rem] shadow-2xl p-8 relative">
                <button @click="activeModal = null" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                <h3 class="text-xl font-bold text-slate-800 mb-4">Connect to PC</h3>
                <input type="text" x-model="tempInputId" placeholder="T-XXXX" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-lg font-bold text-center focus:ring-4 focus:ring-indigo-100 uppercase mb-4 placeholder-gray-300">
                <button @click="startRemoteMode(tempInputId)" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95">Connect Now</button>
            </div>
        </div>

        <div x-show="mode === 'remote'" style="display: none;" 
             class="fixed inset-0 z-[90] bg-slate-950 text-white flex flex-col animate__animated animate__fadeIn">
            
            <div class="px-6 py-4 flex justify-between items-center bg-black/40 backdrop-blur-md border-b border-white/10 safe-area-top">
                <div>
                    <h2 class="text-lg font-bold tracking-wide">REMOTE MODE</h2>
                    <p class="text-xs text-gray-400 font-mono">Linked: <span x-text="remoteTerminalId" class="text-emerald-400 font-bold"></span></p>
                </div>
                <button @click="stopRemoteMode()" class="bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white px-5 py-2.5 rounded-full text-xs font-bold transition-all flex items-center gap-2 border border-red-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    CLOSE
                </button>
            </div>

            <div class="flex-1 relative flex items-center justify-center p-6 bg-black">
                <div class="w-full max-w-sm aspect-square bg-slate-900 rounded-[2.5rem] overflow-hidden border-4 border-slate-800 relative shadow-2xl">
                    <div id="reader-remote" class="w-full h-full object-cover"></div>
                    <div class="absolute inset-0 pointer-events-none border-[3px] border-white/20 rounded-[2.3rem] m-6"></div>
                    <div class="absolute w-full h-0.5 bg-red-500 top-1/2 -translate-y-1/2 animate-pulse shadow-[0_0_20px_rgba(239,68,68,1)] z-10"></div>
                </div>
            </div>

            <div class="p-8 pb-12 bg-gradient-to-t from-slate-900 to-transparent text-center safe-area-bottom">
                <div x-show="lastScanned" class="inline-block bg-emerald-500 text-white px-6 py-3 rounded-full font-mono text-sm shadow-lg shadow-emerald-500/30 animate__animated animate__bounceIn">
                    Sent: <span x-text="lastScanned" class="font-bold"></span>
                </div>
                <p x-show="!lastScanned" class="text-gray-500 text-sm animate-pulse">Align barcode within frame</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="p-3 bg-indigo-600 rounded-2xl text-white shadow-lg shadow-indigo-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </span>
                        New Product
                    </h1>
                    <p class="mt-2 text-slate-500 font-medium ml-1">Add items to your inventory system.</p>
                </div>
                <a href="{{ route('products.index') }}" class="group flex items-center gap-2 px-6 py-3 bg-white text-slate-600 font-bold rounded-xl shadow-sm border border-slate-200 hover:border-indigo-200 hover:text-indigo-600 transition-all">
                    Back to List
                </a>
            </div>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div>
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                Basic Details
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Product Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" x-model="name" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl px-4 py-3.5 font-bold text-slate-800 transition-all placeholder-slate-300" required>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select name="category_id" class="w-full appearance-none bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl px-4 py-3.5 font-bold text-slate-600" required>
                                                <option value="">Select Category...</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Barcode</label>
                                        <div class="relative flex items-center">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                            </div>
                                            <input type="text" name="barcode" x-model="barcode" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl pl-10 pr-12 py-3.5 font-mono font-bold text-slate-700 tracking-wider" placeholder="Scan Remotely">
                                            
                                            <button type="button" @click="activeModal = 'connect'" class="absolute right-2 p-2 bg-white text-indigo-500 rounded-lg hover:bg-indigo-50 border border-slate-100 shadow-sm transition-all" title="Remote Scan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>
                            <h3 class="text-lg font-bold text-slate-800 mb-6">Pricing & Stock</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Sale Price <span class="text-red-500">*</span></label>
                                    <input type="number" name="sale_price" step="0.01" x-model="price" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-emerald-500 rounded-xl px-4 py-3.5 font-bold text-slate-800" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Cost Price</label>
                                    <input type="number" name="cost_price" step="0.01" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-slate-500 rounded-xl px-4 py-3.5 font-bold text-slate-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Stock Qty <span class="text-red-500">*</span></label>
                                    <input type="number" name="qty" x-model="qty" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl px-4 py-3.5 font-bold text-slate-800" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-8 sticky top-8">
                        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2.5rem] p-6 text-white shadow-2xl">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Preview
                            </h3>
                            
                            <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-slate-200/20">
                                <div class="h-48 w-full bg-gray-100 relative overflow-hidden group">
                                    <template x-if="imagePreview">
                                        <img :src="imagePreview" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!imagePreview">
                                        <div class="h-full w-full flex flex-col items-center justify-center text-gray-300 bg-slate-100">
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

                        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Product Image <span class="text-red-500">*</span></label>
                            <label class="group flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-300 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/50 transition-all relative overflow-hidden">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="mb-1 text-sm text-slate-500 font-medium">Click to upload</p>
                                </div>
                                <input type="file" name="image" class="hidden" @change="previewImage" required />
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full py-4 rounded-xl text-white font-black text-lg shadow-xl shadow-indigo-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2"
                                :class="isLoading ? 'bg-slate-800 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'">
                            <span x-show="!isLoading">Save Product</span>
                            <span x-show="isLoading" class="flex items-center gap-2">Saving...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                
                // --- REMOTE SCANNING VARS ---
                activeModal: null,
                tempInputId: '',
                // PERSIST ID IN LOCAL STORAGE
                myTerminalId: localStorage.getItem('pos_create_terminal_id') || ('T-' + Math.floor(1000 + Math.random() * 9000)), 
                remoteTerminalId: '', 
                mode: 'local',
                html5QrCode: null,
                lastScanned: '',

                init() {
                    // Save ID immediately so it sticks
                    localStorage.setItem('pos_create_terminal_id', this.myTerminalId);
                    console.log("Create Product Page Initialized. ID:", this.myTerminalId);

                    // 1. Start Polling for Remote Scans (Mobile -> PC)
                    setInterval(() => {
                        if(this.mode === 'local') this.pollServer();
                    }, 1000); 

                    // 2. Watch Barcode (for Preview)
                    this.$watch('barcode', (val) => {
                        if(val) {
                            try { JsBarcode("#cardBarcode", val, { format: "CODE128", lineColor: "#334155", width: 1.5, height: 40, displayValue: false }); } catch(e) {}
                        } else {
                            document.getElementById('cardBarcode').innerHTML = '';
                        }
                    });
                },

                // --- POLLING LOGIC ---
                pollServer() {
                    fetch(`/pos/remote-poll?terminal_id=${this.myTerminalId}`)
                    .then(res => res.json())
                    .then(data => {
                        if(data.scans && data.scans.length > 0) {
                            // Take the most recent scan and put it in the input
                            const newCode = data.scans[data.scans.length - 1];
                            this.barcode = newCode; 
                            this.notify('Scanned!', 'Barcode received from mobile', 'success');
                            
                            // Play beep if available
                            try {
                                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                                const osc = ctx.createOscillator();
                                osc.connect(ctx.destination);
                                osc.frequency.value = 1000;
                                osc.start(); setTimeout(() => osc.stop(), 100);
                            } catch(e) {}
                        }
                    }).catch(e => {}); 
                },

                // --- MOBILE LOGIC (If this page is opened on phone) ---
                openInputModal() { this.activeModal = 'input_id'; this.tempInputId = ''; },
                
                startRemoteMode(targetId) {
                    if(!targetId) return this.notify('Error', 'ID Required', 'error');
                    this.mode = 'remote';
                    this.remoteTerminalId = targetId;
                    this.activeModal = null;
                    this.startRemoteCamera();
                    this.notify('Connected', 'Scanning to ' + targetId, 'success');
                },

                stopRemoteMode() {
                    this.mode = 'local';
                    if(this.html5QrCode) this.html5QrCode.stop().then(() => this.html5QrCode = null);
                },

                startRemoteCamera() {
                    this.$nextTick(() => {
                        if (this.html5QrCode === null) this.html5QrCode = new Html5Qrcode("reader-remote");
                        this.html5QrCode.start({ facingMode: "environment" }, { fps: 20, qrbox: { width: 250, height: 250 } },
                            (txt) => this.sendRemoteScan(txt), () => {}
                        ).catch(err => this.notify('Camera Error', 'Check permissions', 'error'));
                    });
                },

                sendRemoteScan(barcode) {
                    if(this.lastScanned === barcode) return;
                    this.lastScanned = barcode;
                    fetch('/pos/remote-push', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ terminal_id: this.remoteTerminalId, barcode: barcode })
                    }).then(() => {
                        this.notify('Sent', barcode, 'success');
                        setTimeout(() => this.lastScanned = '', 2000);
                    });
                },

                // --- NOTIFICATIONS & FORM ---
                notify(title, message, type = 'success') {
                    const id = Date.now();
                    const icons = {
                        success: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
                        error: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                        info: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
                    };
                    this.notifications.push({ id, title, message, type, icon: icons[type] });
                    setTimeout(() => { this.notifications = this.notifications.filter(n => n.id !== id) }, 4000);
                },

                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) this.imagePreview = URL.createObjectURL(file);
                },

                submitForm(e) {
                    this.isLoading = true;
                    const formData = new FormData(e.target);
                    fetch(e.target.action, {
                        method: 'POST', body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(async response => {
                        this.isLoading = false;
                        const data = await response.json();
                        if (!response.ok) {
                            if (data.errors) Object.values(data.errors).flat().forEach(err => this.notify('Error', err, 'error'));
                            else this.notify('Error', data.message, 'error');
                            return;
                        }
                        this.notify('Success!', 'Product Saved', 'success');
                        
                        // Form Reset
                        this.name = ''; this.price = ''; this.qty = ''; this.barcode = ''; this.imagePreview = null;
                        e.target.reset();
                    })
                    .catch(error => { this.isLoading = false; this.notify('Error', 'Server Error', 'error'); });
                }
            }
        }
    </script>
</x-app-layout>