<x-app-layout>
    <div x-data="toastNotification()" 
         x-init="initSession('{{ session('success') }}', '{{ session('error') }}')" 
         class="min-h-screen bg-[#F8FAFC] py-10 relative font-[Outfit]">

        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
            <div x-show="show" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] bg-white/90 backdrop-blur-xl border border-white/50"
                 style="display: none;">
                
                <div class="p-4 flex items-start gap-4">
                    <div class="shrink-0">
                        <div x-show="type === 'success'" class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div x-show="type === 'error'" class="h-10 w-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <p class="text-sm font-black text-slate-900" x-text="title"></p>
                        <p class="mt-1 text-sm text-slate-500 font-medium leading-relaxed" x-text="message"></p>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-slate-600"><svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                </div>
                <div class="h-1 w-full bg-slate-100">
                    <div class="h-full transition-all duration-[3000ms] ease-linear w-0" :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'" :style="show ? 'width: 100%' : 'width: 0%'"></div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Inventory Control</h2>
                    <p class="text-slate-500 font-medium mt-1">Manage stock levels and track movements.</p>
                </div>
                
                <div class="flex gap-4">
                    <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                        <div class="bg-blue-50 p-2.5 rounded-xl text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total SKUs</p>
                            <p class="text-xl font-black text-slate-800">{{ $products->count() }}</p>
                        </div>
                    </div>
                    <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                        <div class="bg-orange-50 p-2.5 rounded-xl text-orange-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Low Stock</p>
                            <p class="text-xl font-black text-slate-800">{{ $products->where('qty', '<', 10)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-100 sticky top-6 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </span>
                                Restock Product
                            </h3>
                            <p class="text-slate-500 text-xs mt-1 ml-10">Add new inventory from suppliers.</p>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('stock.store') }}" method="POST" class="space-y-5">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Select Product</label>
                                    <div class="relative">
                                        <select name="product_id" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 sm:text-sm py-3.5 px-4 font-bold text-slate-700 transition-all appearance-none" required>
                                            <option value="" disabled selected>-- Select Item --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->name }} (Currently: {{ $product->qty }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Quantity</label>
                                        <input type="number" name="qty" min="1" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 sm:text-sm py-3.5 px-4 font-bold text-slate-700 transition-all" placeholder="0" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Supplier</label>
                                        <input type="text" name="supplier" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 sm:text-sm py-3.5 px-4 font-bold text-slate-700 transition-all" placeholder="Optional">
                                    </div>
                                </div>

                                <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-4 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-slate-900 hover:bg-black transition-all hover:-translate-y-0.5 active:scale-95">
                                    <span>Confirm Stock In</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2" x-data="{ searchHistory: '' }">
                    <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col h-full">
                        <div class="px-6 py-5 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Movement History</h3>
                                <p class="text-xs text-slate-400 font-bold mt-0.5">Track all ins and outs</p>
                            </div>
                            
                            <div class="relative">
                                <input type="text" x-model="searchHistory" placeholder="Search product..." class="pl-10 pr-4 py-2 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64 transition-all">
                                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto custom-scroll flex-1">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Product Info</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Movement</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Staff</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @forelse($transactions as $t)
                                    <tr class="hover:bg-slate-50 transition-colors" x-show="searchHistory === '' || '{{ strtolower($t->product->name) }}'.includes(searchHistory.toLowerCase())">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-bold text-slate-700">{{ $t->created_at->format('d M') }}</p>
                                            <p class="text-xs text-slate-400 font-bold">{{ $t->created_at->format('h:i A') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-slate-100 overflow-hidden border border-slate-100">
                                                    @if($t->product->image_url)
                                                        <img class="h-full w-full object-cover" src="{{ $t->product->image_url }}" alt="">
                                                    @else
                                                        <div class="h-full w-full flex items-center justify-center text-slate-400 text-[10px] font-bold">IMG</div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-slate-800">{{ $t->product->name }}</div>
                                                    <div class="text-[10px] font-bold text-slate-400 bg-slate-100 inline-block px-1.5 rounded">{{ $t->product->barcode ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($t->type == 'in')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    In: {{ $t->qty }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                                    Out: {{ $t->qty }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="h-6 w-6 rounded-full bg-slate-800 text-white flex items-center justify-center text-[10px] font-bold">
                                                    {{ substr($t->user->name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="text-xs font-bold text-slate-600">{{ $t->user->name ?? 'System' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-900">No transactions recorded</p>
                                            <p class="text-xs text-slate-400">Start by adding stock.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toastNotification() {
            return {
                show: false,
                type: 'success',
                title: '',
                message: '',
                timeout: null,
                initSession(success, error) {
                    if (success) this.trigger('success', 'Stock Updated', success);
                    else if (error) this.trigger('error', 'Operation Failed', error);
                },
                trigger(type, title, message) {
                    this.type = type;
                    this.title = title;
                    this.message = message;
                    this.show = true;
                    if (this.timeout) clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => { this.show = false; }, 3000);
                }
            }
        }
    </script>
</x-app-layout>