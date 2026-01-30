<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Setup Filters
        $availableYears = Sale::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $type = $request->get('type', 'daily'); 
        $query = Sale::with(['user', 'details.product'])->latest();
        $dateTitle = "Overview";

        // 2. Apply Date Logic
        if ($type === 'daily') {
            $date = $request->get('date', date('Y-m-d'));
            $query->whereDate('created_at', $date);
            $dateTitle = Carbon::parse($date)->format('d M Y');
        } elseif ($type === 'monthly') {
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $dateTitle = Carbon::createFromDate(null, $month)->format('F') . " " . $year;
        } elseif ($type === 'yearly') {
            $year = $request->get('year', date('Y'));
            $query->whereYear('created_at', $year);
            $dateTitle = "Year " . $year;
        }

        // 3. Main Stats
        $statsQuery = clone $query;
        $totalRevenue = $statsQuery->sum('final_total');
        $totalTransactions = $statsQuery->count();
        $avgTicket = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // 4. Payment Method Breakdown (Cash vs QR)
        $paymentStats = (clone $query)
            ->select('payment_type', DB::raw('count(*) as count'))
            ->groupBy('payment_type')
            ->pluck('count', 'payment_type');

        // 5. Top Selling Products (The Magic Part)
        // We need to join with sale_details to count product sales within this period
        $saleIds = (clone $query)->pluck('id');
        $topProducts = SaleDetail::whereIn('sale_id', $saleIds)
            ->with('product')
            ->select('product_id', DB::raw('sum(qty) as total_qty'), DB::raw('sum(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $sales = $query->paginate(10)->withQueryString();

        return view('reports.sales', compact(
            'sales', 'availableYears', 'totalRevenue', 'totalTransactions', 
            'avgTicket', 'dateTitle', 'type', 'topProducts', 'paymentStats'
        ));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'daily');
        $query = Sale::with(['user', 'details.product'])->latest();
        $fileName = "Sales_Report";
        $dateTitle = "";

        if ($type === 'daily') {
            $date = $request->get('date', date('Y-m-d'));
            $query->whereDate('created_at', $date);
            $dateTitle = Carbon::parse($date)->format('d F Y');
            $fileName = "Sales_" . $date;
        } elseif ($type === 'monthly') {
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $dateTitle = Carbon::createFromDate(null, $month)->format('F') . " " . $year;
            $fileName = "Sales_" . $year . "_" . $month;
        } elseif ($type === 'yearly') {
            $year = $request->get('year', date('Y'));
            $query->whereYear('created_at', $year);
            $dateTitle = "Year " . $year;
            $fileName = "Sales_" . $year;
        }

        $sales = $query->get();
        $totalRevenue = $sales->sum('final_total');
        $totalCount = $sales->count();

        $pdf = Pdf::loadView('reports.pdf', compact('sales', 'dateTitle', 'totalRevenue', 'totalCount'));
        return $pdf->download($fileName . '.pdf');
    }
}