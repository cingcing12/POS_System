<x-app-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; overflow: hidden; /* Prevent body scroll */ }
        
        /* Sleek Scrollbar */
        .pos-scroll::-webkit-scrollbar { width: 4px; }
        .pos-scroll::-webkit-scrollbar-track { background: transparent; }
        .pos-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .pos-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Glass Effects */
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        /* Scanner Video Fix */
        #reader-remote video { object-fit: cover; border-radius: 1.5rem; width: 100% !important; height: 100% !important; }
    </style>

    <div class="h-screen w-full flex flex-col md:flex-row bg-[#F8FAFC] overflow-hidden relative" 
         x-data="posSystem()" 
         x-init="initSystem()" 
         x-cloak>

        <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-indigo-200/20 rounded-full blur-[100px]"></div>
            <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-purple-200/20 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-[10%] left-[20%] w-[40%] h-[40%] bg-emerald-100/30 rounded-full blur-[100px]"></div>
        </div>

        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none w-full max-w-sm">
            <template x-for="note in notifications" :key="note.id">
                <div class="animate__animated animate__slideInRight animate__faster pointer-events-auto flex items-center gap-4 px-5 py-4 rounded-2xl shadow-2xl backdrop-blur-xl border border-white/40 transition-all transform hover:scale-[1.02]"
                     :class="{
                        'bg-slate-900/95 text-white shadow-slate-900/20': note.type === 'success',
                        'bg-red-500/95 text-white shadow-red-500/20': note.type === 'error',
                        'bg-white/95 text-slate-800 shadow-slate-200/50': note.type === 'info',
                        'bg-orange-500/95 text-white shadow-orange-500/20': note.type === 'warning'
                     }">
                     <div class="shrink-0">
                        <template x-if="note.type === 'success'"><svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></template>
                        <template x-if="note.type === 'error'"><svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></template>
                        <template x-if="note.type === 'info'"><svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold leading-none mb-1" x-text="note.title"></h4>
                        <p class="text-xs opacity-90 font-medium" x-text="note.message"></p>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="activeModal === 'connect'" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-md transition-opacity" @click="activeModal = null"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl p-8 max-w-sm w-full relative animate__animated animate__zoomIn animate__faster" @click.stop>
                <div class="text-center space-y-6">
                    <div class="w-20 h-20 bg-gradient-to-tr from-indigo-500 to-purple-600 text-white rounded-[1.5rem] flex items-center justify-center mx-auto shadow-lg shadow-indigo-500/30">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">Scanner Sync</h3>
                        <p class="text-slate-500 text-sm font-medium mt-2">Connect your mobile as a barcode scanner</p>
                    </div>
                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group hover:border-indigo-100 transition-colors">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">TERMINAL ID</p>
                        <div class="text-4xl font-mono font-black text-slate-800 tracking-wider group-hover:text-indigo-600 transition-colors" x-text="myTerminalId"></div>
                    </div>
                    <button @click="activeModal = 'terminal_input'" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl hover:bg-black transition-all shadow-xl shadow-slate-900/10 active:scale-95">
                        Connect Other Device
                    </button>
                </div>
            </div>
        </div>

        <div x-show="activeModal === 'terminal_input'" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-md" @click="activeModal = null"></div>
            <div class="bg-white rounded-[2.5rem] shadow-2xl p-8 max-w-sm w-full relative animate__animated animate__fadeInUp animate__faster" @click.stop>
                <h3 class="text-xl font-black text-slate-800 mb-6 text-center">Enter Terminal ID</h3>
                <input type="text" x-model="inputTerminalId" class="w-full bg-slate-50 border-0 rounded-2xl px-4 py-5 font-black text-slate-800 text-center text-3xl mb-6 focus:ring-4 focus:ring-indigo-100 uppercase tracking-widest placeholder-slate-300" placeholder="T-XXXX">
                <div class="grid grid-cols-2 gap-3">
                    <button @click="activeModal = 'connect'" class="bg-white border border-slate-200 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-50">Back</button>
                    <button @click="startRemoteMode(inputTerminalId)" class="bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Connect</button>
                </div>
            </div>
        </div>

        <div x-show="activeModal === 'payment'" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="cancelPayment()"></div>
            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden max-w-sm w-full relative animate__animated animate__zoomIn animate__faster" @click.stop>
                <button @click="cancelPayment()" class="absolute top-6 right-6 p-2 bg-white/10 hover:bg-white/20 rounded-full text-white transition-colors z-20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="bg-slate-900 pt-12 pb-10 px-10 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-500/20 to-purple-500/20"></div>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px] mb-3 relative z-10">Total to Pay</p>
                    <h3 class="text-6xl font-black text-white tracking-tighter relative z-10" x-text="'$' + Number(paymentData.amount).toFixed(2)"></h3>
                </div>
                
                <div class="p-10 flex flex-col items-center bg-white">
                    <div id="qrcode-container" class="p-4 bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 mb-8"></div>
                    <div class="flex items-center gap-3 text-slate-500 bg-slate-50 px-5 py-3 rounded-full text-xs font-bold animate-pulse border border-slate-100">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div> 
                        Scanning for payment...
                    </div>
                </div>
            </div>
        </div>

        <div x-show="activeModal === 'success'" class="fixed inset-0 z-50 flex items-center justify-center p-4">
             <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
             <div class="bg-white rounded-[3rem] p-12 text-center max-w-sm w-full relative overflow-hidden shadow-2xl animate__animated animate__bounceIn">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <svg class="w-12 h-12 animate__animated animate__rubberBand animate__delay-1s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-800 mb-2 tracking-tight">Payment Received</h3>
                <p class="text-slate-400 font-medium mb-10 text-sm font-mono bg-slate-50 inline-block px-3 py-1 rounded-lg" x-text="'INV-' + paymentData.invoice"></p>
                <button @click="startNewOrder()" class="w-full bg-slate-900 text-white font-bold py-5 rounded-2xl hover:bg-black shadow-xl hover:shadow-2xl hover:-translate-y-1 transform active:scale-95 active:translate-y-0 transition-all">
                    Start New Order
                </button>
             </div>
        </div>

        <div x-show="mode === 'remote'" 
             class="fixed inset-0 z-[60] bg-black flex flex-col items-center justify-center p-6 animate__animated animate__fadeIn">
            
            <div class="relative w-full max-w-md aspect-[3/4] bg-slate-900 rounded-[3rem] border-8 border-slate-800 shadow-2xl overflow-hidden mb-8">
                 <div id="reader-remote" class="w-full h-full bg-black"></div>
                 
                 <div class="absolute inset-0 pointer-events-none">
                     <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-black/80 to-transparent"></div>
                     <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-black/80 to-transparent"></div>
                     
                     <div class="absolute top-1/2 left-8 right-8 h-0.5 bg-red-500 shadow-[0_0_20px_rgba(239,68,68,1)] animate-pulse"></div>
                     
                     <div class="absolute top-8 left-8 w-12 h-12 border-t-4 border-l-4 border-white/50 rounded-tl-2xl"></div>
                     <div class="absolute top-8 right-8 w-12 h-12 border-t-4 border-r-4 border-white/50 rounded-tr-2xl"></div>
                     <div class="absolute bottom-8 left-8 w-12 h-12 border-b-4 border-l-4 border-white/50 rounded-bl-2xl"></div>
                     <div class="absolute bottom-8 right-8 w-12 h-12 border-b-4 border-r-4 border-white/50 rounded-br-2xl"></div>
                 </div>
            </div>

            <div class="text-center">
                <h2 class="text-white font-black text-2xl tracking-widest uppercase mb-2">Scanner Active</h2>
                <div class="inline-flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full mb-8 backdrop-blur-md border border-white/10">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-white/60 font-mono text-sm" x-text="'Linked: ' + remoteTerminalId"></span>
                </div>
                <button @click="stopRemoteMode()" class="bg-white text-black px-12 py-4 rounded-full font-bold hover:bg-slate-200 transition-colors shadow-[0_0_30px_rgba(255,255,255,0.2)] active:scale-95">
                    Close Scanner
                </button>
            </div>
        </div>

        <div class="flex-1 flex flex-col h-full min-w-0 relative z-10 pl-4 py-4 md:pl-6 md:py-6">
            
            <div class="pr-4 md:pr-6 mb-4 z-20">
                <div class="glass-panel rounded-[2rem] p-3 pl-5 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
                    
                    <div class="relative w-full md:max-w-md group">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model="search" id="searchInput" autofocus placeholder="Search items..." 
                               class="w-full pl-12 pr-4 py-3.5 bg-slate-100/50 border-none rounded-2xl font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all">
                    </div>

                    <div class="flex gap-2 overflow-x-auto no-scrollbar w-full md:w-auto px-1 py-1">
                        <button @click="selectedCategory = 'all'" 
                                :class="selectedCategory === 'all' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20 scale-105' : 'bg-transparent text-slate-500 hover:bg-slate-100'" 
                                class="px-6 py-3 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300">All</button>
                        @foreach($categories as $category)
                        <button @click="selectedCategory = {{ $category->id }}" 
                                :class="selectedCategory === {{ $category->id }} ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20 scale-105' : 'bg-transparent text-slate-500 hover:bg-slate-100'" 
                                class="px-6 py-3 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300">{{ $category->name }}</button>
                        @endforeach
                    </div>
                    
                    <button @click.stop="activeModal = 'connect'" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto pr-4 md:pr-6 pb-20 pos-scroll">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 md:gap-6">
                    @foreach($products as $product)
                    <div x-show="matchesSearch('{{ strtolower($product->name) }}', '{{ $product->barcode ?? $product->id }}', {{ $product->category_id ?? 'null' }})"
                         @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->sale_price }}, '{{ $product->image_url }}')"
                         class="group bg-white rounded-[2rem] p-3 cursor-pointer shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full flex flex-col">
                        
                        <div class="aspect-[4/3] w-full rounded-[1.5rem] overflow-hidden bg-slate-50 mb-3 relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            
                            <div class="absolute top-3 left-3">
                                <template x-if="products.find(p => p.id === {{ $product->id }}).qty <= 0">
                                    <span class="px-2 py-1 bg-slate-900/90 text-white text-[10px] font-black uppercase tracking-wider rounded-md backdrop-blur-md">Sold Out</span>
                                </template>
                            </div>
                        </div>
                        
                        <div class="px-1 flex-1 flex flex-col justify-between">
                            <h3 class="text-sm font-bold text-slate-700 leading-snug mb-2 group-hover:text-indigo-600 transition-colors">{{ $product->name }}</h3>
                            <div class="flex justify-between items-center">
                                <p class="text-lg font-black text-slate-900">${{ number_format($product->sale_price, 2) }}</p>
                                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div x-show="!mobileCartOpen && cart.length > 0" 
             @click="mobileCartOpen = true"
             class="md:hidden fixed bottom-6 left-6 right-6 z-40 bg-slate-900 text-white p-4 rounded-[2rem] shadow-2xl flex justify-between items-center animate__animated animate__fadeInUp cursor-pointer active:scale-95 transition-transform">
            <div class="flex items-center gap-4">
                <div class="bg-white text-slate-900 w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg" x-text="cart.length"></div>
                <div class="flex flex-col">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total</span>
                    <span class="font-black text-xl" x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
            </div>
            <span class="font-bold text-sm bg-white/10 px-4 py-2 rounded-xl">Checkout</span>
        </div>

        <div :class="mobileCartOpen ? 'fixed inset-0 z-50 bg-white' : 'hidden md:flex'"
             class="md:w-[420px] md:m-4 md:rounded-[2.5rem] flex-col h-[calc(100vh-2rem)] bg-white shadow-2xl transition-all relative overflow-hidden border border-slate-100">
             
             <div class="p-6 pb-2 bg-white z-20">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Current Order</h2>
                    <button @click="mobileCartOpen = false" class="md:hidden p-2 bg-slate-50 rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <button @click.stop="activeModal = 'connect'" class="hidden md:flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100 hover:border-indigo-200 transition-colors group">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[10px] font-mono font-bold text-slate-400 group-hover:text-indigo-600" x-text="myTerminalId"></span>
                    </button>
                </div>
                
                <div class="flex justify-between items-center pb-4 border-b border-slate-50">
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-wider" x-text="cart.length + ' Items'"></p>
                    <button @click="clearCart()" x-show="cart.length > 0" class="text-red-500 text-[10px] font-black uppercase hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors tracking-wide">Clear All</button>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto px-6 py-2 space-y-3 pos-scroll bg-white">
                <template x-if="cart.length === 0">
                    <div class="flex flex-col items-center justify-center h-[50vh] text-center opacity-40">
                        <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-1">No items yet</h3>
                        <p class="text-xs text-slate-400 font-medium">Scan a barcode or select from menu</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="animate__animated animate__fadeInRight animate__faster bg-slate-50 p-3 rounded-[1.25rem] flex items-center gap-3 group hover:bg-indigo-50/50 transition-colors">
                        <img :src="item.image" class="h-14 w-14 rounded-xl object-cover bg-white shadow-sm">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-0.5">
                                <p class="text-sm font-bold text-slate-800 truncate pr-2" x-text="item.name"></p>
                                <p class="text-sm font-black text-slate-900" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold mb-1.5 uppercase tracking-wide" x-text="'$' + item.price.toFixed(2) + ' / ea'"></p>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex items-center bg-white rounded-lg shadow-sm h-7 border border-slate-100/50">
                                    <button @click="updateQty(index, -1)" class="w-7 h-full flex items-center justify-center text-slate-400 hover:text-red-600 rounded-l-lg transition-colors">-</button>
                                    <span class="w-6 text-center text-xs font-black text-slate-700" x-text="item.qty"></span>
                                    <button @click="updateQty(index, 1)" class="w-7 h-full flex items-center justify-center text-slate-400 hover:text-emerald-600 rounded-r-lg transition-colors">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-6 bg-white border-t border-slate-50 z-20 shadow-[0_-10px_60px_rgba(0,0,0,0.05)]">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <span class="text-slate-400 font-bold text-[10px] uppercase tracking-widest block mb-1">Total Amount</span>
                        <span class="text-slate-300 text-[10px] font-medium">Tax incl.</span>
                    </div>
                    <span class="text-4xl font-black text-slate-900 tracking-tighter" x-text="'$' + total.toFixed(2)"></span>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button @click="paymentType = 'cash'" 
                            :class="paymentType === 'cash' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'" 
                            class="py-3.5 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Cash
                    </button>
                    <button @click="paymentType = 'khqr'" 
                            :class="paymentType === 'khqr' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'" 
                            class="py-3.5 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg> KHQR
                    </button>
                </div>

                <button @click="processPayment()" :disabled="cart.length === 0 || processing" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-5 rounded-2xl text-lg shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 disabled:opacity-50 disabled:shadow-none flex items-center justify-center gap-3 transition-all transform active:scale-[0.98]">
                    <span x-show="!processing" x-text="paymentType === 'khqr' ? 'Generate QR' : 'Pay Now'"></span>
                    <span x-show="processing" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                </button>
            </div>
        </div>
    </div>

    <script>    
    let html5QrCode = null;

    function posSystem() {
        return {
            search: '',
            selectedCategory: 'all',
            cart: [],
            products: @json($products), 
            paymentType: 'cash',
            processing: false,
            mobileCartOpen: false, 
            mode: 'local',
            myTerminalId: localStorage.getItem('pos_terminal_id') || 'T-' + Math.floor(1000 + Math.random() * 9000), 
            remoteTerminalId: '', 
            lastScanned: '',
            notifications: [],
            activeModal: null, 
            inputTerminalId: '',
            paymentData: { amount: 0, invoice: '', qrString: '', md5: '' },
            paymentPollTimer: null,
            scanBuffer: '',
            scanTimeout: null,
            
            // --- NEW: State for locking and duplicate prevention ---
            isScanning: false, 
            lastProcessedBarcode: '',
            lastProcessedTime: 0,

            initSystem() {
                localStorage.setItem('pos_terminal_id', this.myTerminalId);
                
                setInterval(() => {
                    if(this.mode === 'local' && !this.activeModal) {
                        this.pollServerForScans();
                    } else if (this.mode === 'remote') {
                        this.pollServerForCommands();
                    }
                }, 1000);

                setInterval(() => { if(this.mode === 'local') this.syncStock(); }, 15000);

                this.$watch('search', (value) => {
                    if (value.length > 2) this.checkAndAddProduct(value);
                });

                // Global Barcode Listener (USB Scanner)
                window.addEventListener('keydown', (e) => {
                    if (this.activeModal) return; 
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                    
                    // If Enter is pressed, process buffer
                    if (e.key === 'Enter') {
                        if (this.scanBuffer.length > 1) { 
                            this.checkAndAddProduct(this.scanBuffer, true);
                            this.scanBuffer = ''; 
                        }
                        return;
                    }
                    
                    // Capture scanner characters
                    if (e.key.length === 1) {
                        this.scanBuffer += e.key;
                        clearTimeout(this.scanTimeout);
                        this.scanTimeout = setTimeout(() => { this.scanBuffer = ''; }, 100); 
                    }
                });
            },

            notify(title, message, type = 'success') {
                const id = Date.now();
                this.notifications.push({ id, title, message, type });
                setTimeout(() => { 
                    this.notifications = this.notifications.filter(n => n.id !== id); 
                }, 4000);
            },

            // --- SMART SCANNING LOGIC ---
            checkAndAddProduct(term, fromCamera = false) {
                // 1. STOP: If currently adding an item, block everything else
                if (this.isScanning) return;

                const cleanTerm = term.trim().toLowerCase();
                if(!cleanTerm) return;

                // 2. DUPLICATE CHECK: If it is the SAME item scanned within 2 seconds, ignore it
                // This stops the "multiple scan" issue but allows "fast scan" for different items
                if (cleanTerm === this.lastProcessedBarcode && (Date.now() - this.lastProcessedTime < 2000)) {
                    console.log("Duplicate scan blocked");
                    return;
                }

                const product = this.products.find(p => String(p.barcode).toLowerCase() === cleanTerm || String(p.id) === cleanTerm || p.name.toLowerCase() === cleanTerm);

                if (product) {
                    // 3. LOCK: Prevent any new input
                    this.isScanning = true;

                    // 4. PROCESS: Add to cart
                    this.addToCart(product.id, product.name, product.sale_price, product.image_url);
                    this.playBeep();
                    
                    if(fromCamera) this.notify('Added', product.name, 'success');
                    this.search = '';

                    // 5. UPDATE HISTORY: Remember what we just scanned
                    this.lastProcessedBarcode = cleanTerm;
                    this.lastProcessedTime = Date.now();

                    // 6. UNLOCK: Allow next scan quickly (200ms)
                    // This creates a smooth "Scan -> Beep -> Ready" flow
                    setTimeout(() => {
                        this.isScanning = false;
                    }, 200); 

                } else if(fromCamera) {
                    this.isScanning = true;
                    this.notify('Not Found', 'Product not in database', 'error');
                    setTimeout(() => { this.isScanning = false; }, 500);
                }
            },

            addToCart(id, name, price, image) {
                let realProduct = this.products.find(p => p.id === id);
                let currentStock = realProduct ? realProduct.qty : 0;
                let item = this.cart.find(i => i.id === id);
                if (item) {
                    if(item.qty >= currentStock) return this.notify('Stock Limit', 'Max available stock reached', 'warning');
                    item.qty++;
                } else {
                    if(currentStock < 1) return this.notify('Out of Stock', 'Item unavailable', 'error');
                    this.cart.push({ id, name, price, image, qty: 1 });
                }
            },

            async pollServerForScans() {
                try {
                    const res = await fetch(`/pos/remote-poll?terminal_id=${this.myTerminalId}`);
                    const data = await res.json();
                    
                    if(data.scans && data.scans.length > 0) {
                        // Queue scans one by one
                        for (const barcode of data.scans) {
                            // Wait if system is busy
                            while(this.isScanning) { await new Promise(r => setTimeout(r, 50)); }
                            this.checkAndAddProduct(barcode, true);
                            // Small buffer between remote items
                            await new Promise(r => setTimeout(r, 300)); 
                        }
                    }
                } catch(e) {}
            },

            // ... (Standard helper functions below) ...
            startRemoteMode(targetId) {
                if(!targetId) return this.notify('Error', 'Terminal ID required', 'error');
                this.mode = 'remote';
                this.remoteTerminalId = targetId; 
                this.activeModal = null; 
                this.notify('Connected', 'Remote Scanner Active', 'success');
                
                this.$nextTick(() => {
                    html5QrCode = new Html5Qrcode("reader-remote");
                    html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } },
                        (decodedText) => { this.sendRemoteScan(decodedText); }, () => {}
                    ).catch(err => {});
                });
            },

            sendRemoteScan(barcode) {
                if(this.lastScanned === barcode) return; 
                this.playBeep();
                this.lastScanned = barcode;
                fetch('/pos/remote-push', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ terminal_id: this.remoteTerminalId, barcode: barcode })
                }).then(() => {
                    this.notify('Scanned', barcode, 'success');
                    setTimeout(() => { this.lastScanned = ''; }, 2000); 
                });
            },

            stopRemoteMode() {
                this.mode = 'local';
                if(html5QrCode) html5QrCode.stop().then(() => { html5QrCode = null; });
            },

            matchesSearch(name, barcode, categoryId) { 
                const term = this.search.toLowerCase();
                if(this.mode === 'remote') return false; 
                return (!term || name.toLowerCase().includes(term) || String(barcode).includes(term)) && (this.selectedCategory === 'all' || this.selectedCategory === categoryId);
            },

            updateQty(index, change) {
                let item = this.cart[index];
                let currentProduct = this.products.find(p => p.id === item.id);
                if (change > 0 && item.qty >= currentProduct.qty) return this.notify('Limit', 'Max stock reached', 'warning');
                if (item.qty + change > 0) item.qty += change;
                else this.cart.splice(index, 1);
            },

            clearCart() { this.cart = []; },

            syncStock() {
                fetch('/pos/get-stock').then(res => res.json()).then(data => {
                    data.forEach(item => { let p = this.products.find(x => x.id === item.id); if(p) p.qty = item.qty; });
                }).catch(e => {});
            },

            processPayment() {
                if (this.paymentType === 'khqr') this.showKHQR();
                else this.submitSale();
            },

            async showKHQR() {
                this.processing = true;
                try {
                    const res = await fetch('/pos/generate-khqr', { 
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ total: this.total })
                    });
                    const data = await res.json();
                    this.paymentData = { amount: data.amount, invoice: data.invoice_number, qrString: data.qrString, md5: data.md5 };
                    this.activeModal = 'payment';
                    this.$nextTick(() => {
                        document.getElementById("qrcode-container").innerHTML = "";
                        new QRCode(document.getElementById("qrcode-container"), {
                            text: data.qrString, width: 220, height: 220,
                            colorDark : "#000000", colorLight : "#ffffff", correctLevel : QRCode.CorrectLevel.H
                        });
                    });
                    this.startPaymentCheck(data.md5, data.amount);
                } catch(e) {
                    this.notify('Error', 'Failed to generate QR', 'error');
                } finally {
                    this.processing = false;
                }
            },

            cancelPayment() {
                this.activeModal = null;
                clearTimeout(this.paymentPollTimer);
            },

            startPaymentCheck(md5, amount) {
                const check = () => {
                    if (this.activeModal !== 'payment') return; 
                    fetch('/pos/check-transaction', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ md5: md5, amount: amount })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status === 'PAID') {
                            this.activeModal = null; 
                            this.submitSale(); 
                        } else {
                            this.paymentPollTimer = setTimeout(check, 1500); 
                        }
                    })
                    .catch(() => { this.paymentPollTimer = setTimeout(check, 3000); });
                };
                check();
            },

            submitSale() {
                this.processing = true;
                fetch('/pos/store', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        cart: this.cart, 
                        payment_type: this.paymentType, 
                        total: this.total, 
                        invoice_number: this.paymentData.invoice || null 
                    })
                }).then(res => res.json()).then(data => {
                    this.processing = false;
                    if(data.success) {
                        this.cart.forEach(cartItem => {
                            let product = this.products.find(p => p.id === cartItem.id);
                            if(product) product.qty = Math.max(0, product.qty - cartItem.qty);
                        });
                        this.paymentData.invoice = data.invoice;
                        this.activeModal = 'success';
                        this.clearCart();
                    } else {
                        this.notify('Error', data.message, 'error');
                        this.syncStock();
                    }
                });
            },

            startNewOrder() {
                this.activeModal = null;
                this.paymentData = { amount: 0, invoice: '', qrString: '', md5: '' };
                this.paymentType = 'cash';
                this.clearCart();
                document.getElementById('searchInput').focus(); 
            },

            playBeep() { try { const a=new AudioContext();const o=a.createOscillator();o.connect(a.destination);o.start();setTimeout(()=>o.stop(),100); } catch(e){} },
            get subtotal() { return this.cart.reduce((s, i) => s + (i.price * i.qty), 0); },
            get total() { return this.subtotal; }
        }
    }
</script>
</x-app-layout>