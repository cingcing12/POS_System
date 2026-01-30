<x-app-layout>
    <div x-data="toastNotification()" 
         x-init="initSession('{{ session('success') }}', '{{ session('error') }}')" 
         class="min-h-screen bg-[#F3F4F6] py-10 relative font-[Outfit]">

        <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
            <div x-show="show" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] bg-white/95 backdrop-blur-xl border border-white/50"
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
            
            <div class="mb-10" x-data="{ reportType: '{{ request('type', 'daily') }}' }">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-12 relative overflow-hidden shadow-2xl shadow-slate-200">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600 rounded-full blur-[100px] opacity-40 -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-600 rounded-full blur-[100px] opacity-30 -ml-16 -mb-16"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
                        <div>
                            <p class="text-indigo-300 font-bold text-xs uppercase tracking-[0.2em] mb-2">Financial Overview</p>
                            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                                Sales Performance
                            </h1>
                            <p class="text-slate-400 mt-3 max-w-lg text-sm leading-relaxed">
                                Track your revenue streams, monitor top-selling products, and analyze transaction history in real-time.
                            </p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-md p-1 rounded-2xl flex border border-white/10">
                            <button @click="reportType = 'daily'" :class="reportType === 'daily' ? 'bg-white text-slate-900 shadow-lg' : 'text-white/70 hover:text-white'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Daily</button>
                            <button @click="reportType = 'monthly'" :class="reportType === 'monthly' ? 'bg-white text-slate-900 shadow-lg' : 'text-white/70 hover:text-white'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Monthly</button>
                            <button @click="reportType = 'yearly'" :class="reportType === 'yearly' ? 'bg-white text-slate-900 shadow-lg' : 'text-white/70 hover:text-white'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all">Yearly</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-2 mt-8 mb-8">
                    <form method="GET" action="{{ route('reports.sales') }}" class="flex flex-col md:flex-row items-center gap-2 p-2">
                        <input type="hidden" name="type" x-model="reportType">

                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-2" x-cloak>
                            <div x-show="reportType === 'daily'" class="md:col-span-3 transition-all duration-300">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                                </div>
                            </div>

                            <div x-show="reportType === 'monthly'" class="contents">
                                <div class="md:col-span-2">
                                    <select name="month" class="w-full py-3 px-4 bg-slate-50 border-none rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                        @for($m=1; $m<=12; $m++) 
                                            <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option> 
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <select name="year" class="w-full py-3 px-4 bg-slate-50 border-none rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                        @foreach($availableYears as $year) 
                                            <option value="{{ $year }}" {{ request('year', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div x-show="reportType === 'yearly'" class="md:col-span-3">
                                <select name="year" class="w-full py-3 px-4 bg-slate-50 border-none rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 cursor-pointer" :disabled="reportType !== 'yearly'">
                                    @foreach($availableYears as $year) 
                                        <option value="{{ $year }}" {{ request('year', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 flex-1 md:flex-none transition-all hover:-translate-y-0.5">
                                Apply Filter
                            </button>
                            <a :href="'{{ route('reports.export') }}?' + new URLSearchParams(new FormData($el.closest('form'))).toString()" class="bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 px-4 py-3 rounded-xl font-bold transition-all flex items-center justify-center hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full transition-transform group-hover:scale-150"></div>
                            <div class="relative z-10">
                                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Revenue</p>
                                <h3 class="text-3xl font-black text-slate-800 mt-1">${{ number_format($totalRevenue, 2) }}</h3>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full transition-transform group-hover:scale-150"></div>
                            <div class="relative z-10">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Transactions</p>
                                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ number_format($totalTransactions) }}</h3>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-50 rounded-full transition-transform group-hover:scale-150"></div>
                            <div class="relative z-10">
                                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Avg. Ticket</p>
                                <h3 class="text-3xl font-black text-slate-800 mt-1">${{ number_format($avgTicket, 2) }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                            <h3 class="font-bold text-slate-800">Recent Transactions</h3>
                            <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-wide">{{ $dateTitle }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-8 py-4">Invoice</th>
                                        <th class="px-8 py-4">Items</th>
                                        <th class="px-8 py-4 text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($sales as $sale)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800 font-mono text-sm">#{{ $sale->invoice_number }}</p>
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $sale->created_at->format('h:i A') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-sm font-bold text-slate-600">
                                                {{ $sale->details->first()->product->name ?? 'Unknown' }}
                                                @if($sale->details->count() > 1) 
                                                    <span class="ml-1 text-[10px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 font-bold">+{{ $sale->details->count() - 1 }} more</span> 
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <div class="w-1.5 h-1.5 rounded-full {{ $sale->payment_type == 'cash' ? 'bg-green-500' : 'bg-purple-500' }}"></div>
                                                <span class="text-[10px] text-slate-400 uppercase font-bold">{{ $sale->payment_type }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-right font-black text-slate-900 text-base">
                                            ${{ number_format($sale->final_total, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-12 text-slate-400 text-sm font-medium">No transactions found for this period.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($sales->hasPages())
                        <div class="px-8 py-6 border-t border-slate-50">{{ $sales->links() }}</div>
                        @endif
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 h-full">
                        <h3 class="font-bold text-slate-800 mb-8 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-500 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            Top Performers
                        </h3>
                        
                        <div class="space-y-6">
                            @forelse($topProducts as $index => $item)
                            <div class="group">
                                <div class="flex justify-between items-end mb-2">
                                    <div>
                                        <span class="text-xs font-bold text-slate-400 mr-2">#{{ $index + 1 }}</span>
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $item->product->name ?? 'Unknown' }}</span>
                                    </div>
                                    <span class="text-xs font-black text-slate-900">{{ $item->total_qty }} <span class="text-slate-400 font-medium">Sold</span></span>
                                </div>
                                <div class="w-full bg-slate-50 rounded-full h-2.5 overflow-hidden">
                                    @php $percent = ($item->total_qty / $topProducts->max('total_qty')) * 100; @endphp
                                    <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000 ease-out group-hover:bg-indigo-600" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-slate-400 text-xs font-bold">No sales data yet.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6">Payment Methods</h3>
                        <div class="space-y-4">
                            @foreach($paymentStats as $method => $count)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold
                                        {{ $method == 'cash' ? 'bg-gradient-to-br from-green-400 to-green-600 shadow-lg shadow-green-200' : 'bg-gradient-to-br from-purple-400 to-purple-600 shadow-lg shadow-purple-200' }}">
                                        {{ substr($method, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-600 uppercase tracking-wide">{{ $method }}</span>
                                </div>
                                <span class="text-lg font-black text-slate-900">{{ $count }}</span>
                            </div>
                            @endforeach
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
                    if (success) this.trigger('success', 'Report Generated', success);
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