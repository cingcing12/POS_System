<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>{{ $greeting }}, {{ Auth::user()->name }}</span>
                        <span class="text-2xl">👋</span>
                    </h1>
                    <p class="text-slate-500 font-medium mt-2">Here is what’s happening in your store today.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('pos.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-200 flex items-center gap-2 transition-all hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span>Open POS</span>
                    </a>
                    <div class="bg-white px-4 py-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-3">
                        <div class="bg-slate-100 p-2 rounded-xl text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">DATE</p>
                            <p class="text-sm font-bold text-slate-800">{{ date('d M, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                
                <div class="relative overflow-hidden rounded-3xl p-6 shadow-xl bg-gradient-to-br from-indigo-600 to-violet-700 text-white group">
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start">
                            <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="bg-emerald-400/20 text-emerald-100 text-xs font-bold px-2 py-1 rounded-lg border border-emerald-400/30">Today</span>
                        </div>
                        <p class="mt-6 text-indigo-100 text-xs font-bold uppercase tracking-widest">Total Sales</p>
                        <h3 class="text-4xl font-black mt-1">${{ number_format($todaySales, 2) }}</h3>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-3xl p-6 shadow-xl bg-white border border-slate-100 group">
                    <div class="absolute right-0 top-0 h-full w-1.5 bg-blue-500"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Transactions</p>
                            <h3 class="text-4xl font-black text-slate-800 mt-2">{{ number_format($todayTransactions) }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs font-bold text-slate-400">Processed Today</p>
                </div>

                <div class="relative overflow-hidden rounded-3xl p-6 shadow-xl bg-white border border-slate-100 group">
                    <div class="absolute right-0 top-0 h-full w-1.5 bg-red-500"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Low Stock</p>
                            <h3 class="text-4xl font-black text-slate-800 mt-2">{{ $lowStockCount }}</h3>
                        </div>
                        <div class="p-3 bg-red-50 text-red-600 rounded-2xl relative">
                            <span class="absolute top-0 right-0 flex h-3 w-3 -mt-1 -mr-1">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs font-bold text-slate-400">Products below 10 qty</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl -mr-10 -mt-10"></div>
                        
                        <h3 class="text-lg font-bold mb-1">Inventory Status</h3>
                        <p class="text-slate-400 text-sm mb-6">Total SKU count in database.</p>
                        
                        <div class="flex items-end gap-2">
                            <span class="text-5xl font-black">{{ $totalProducts }}</span>
                            <span class="text-sm font-bold text-slate-400 mb-2">Products</span>
                        </div>

                        <a href="{{ route('products.create') }}" class="mt-6 w-full py-3 bg-white text-slate-900 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-blue-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add New Product
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Critical Inventory</h3>
                                <p class="text-xs text-slate-400 font-bold mt-1">Items requiring immediate attention</p>
                            </div>
                            <a href="{{ route('stock.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                                View All Stock
                            </a>
                        </div>

                        <div class="p-4 space-y-3">
                            @forelse($lowStock as $product)
                            <div class="flex items-center gap-4 p-3 hover:bg-slate-50 rounded-2xl transition-colors group border border-transparent hover:border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                                    @if($product->image_url)
                                        <img src="{{ asset($product->image_url) }}" class="w-full h-full object-cover rounded-xl">
                                    @else
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <h4 class="font-bold text-slate-700">{{ $product->name }}</h4>
                                        <span class="text-xs font-black {{ $product->qty == 0 ? 'text-red-500' : 'text-amber-500' }}">
                                            {{ $product->qty }} Left
                                        </span>
                                    </div>
                                    
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        @php 
                                            // Calculate percentage (assuming 20 is "healthy" max for this visual)
                                            $percent = min(100, ($product->qty / 20) * 100);
                                            $color = $product->qty == 0 ? 'bg-red-500' : ($product->qty < 5 ? 'bg-red-400' : 'bg-amber-400');
                                        @endphp
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>

                                <a href="{{ route('stock.index') }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>
                            </div>
                            @empty
                            <div class="text-center py-10">
                                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h3 class="text-slate-800 font-bold">All Good!</h3>
                                <p class="text-slate-400 text-sm">Inventory levels are healthy.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>