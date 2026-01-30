<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\Str; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use KHQR\BakongKHQR;
use KHQR\Models\MerchantInfo;
use KHQR\Helpers\KHQRData;
use Carbon\Carbon;

class PosController extends Controller
{
    // --- CONFIGURATION ---
    private $bakongBaseUrl = 'https://api-bakong.nbc.gov.kh';
    private $bakongToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImlkIjoiMTgxMTVhM2M2MjUxNDhiZiJ9LCJpYXQiOjE3NjY0NTQyNjMsImV4cCI6MTc3NDIzMDI2M30.K2HHJNf6CuAuSQmrJ0l6-yFTBL6IbXFQOF_NI0DV0WU'; 
    private $myMerchantId = 'sokpheak_vong@bkrt';

    public function index() {
        $products = Product::all(); 
        $categories = Category::all(); 
        return view('pos.index', compact('products', 'categories'));
    }

    // =========================================================================
    //  REAL-TIME REMOTE SCANNER LOGIC 
    // =========================================================================

    public function pushRemoteScan(Request $request) {
        $request->validate([
            'terminal_id' => 'required|string',
            'barcode' => 'required|string'
        ]);

        $key = 'pos_cmd_' . $request->terminal_id;
        $scans = Cache::get($key, []);
        $scans[] = $request->barcode;
        Cache::put($key, $scans, 20);

        return response()->json(['success' => true]);
    }

    public function pollRemoteScans(Request $request) {
        $request->validate(['terminal_id' => 'required|string']);

        $key = 'pos_cmd_' . $request->terminal_id;
        $scans = Cache::get($key, []);

        if (!empty($scans)) {
            Cache::forget($key);
            return response()->json(['scans' => $scans]);
        }

        return response()->json(['scans' => []]);
    }

    // =========================================================================
    //  PAYMENT & SALES LOGIC
    // =========================================================================

    public function generateKhqr(Request $request) {
        $request->validate(['total' => 'required|numeric']);

        try {
            $invoiceNumber = 'INV-' . strtoupper(uniqid());

            $merchantInfo = new MerchantInfo(
                merchantID: $this->myMerchantId,
                bakongAccountID: 'sokpheak_vong@bkrt',
                merchantName: 'Sokpheak Shop',
                merchantCity: 'Phnom Penh',
                acquiringBank: 'Bakong',
                amount: (float)$request->total,
                currency: KHQRData::CURRENCY_USD,
                billNumber: $invoiceNumber, 
                mobileNumber: '85510917628',
                storeLabel: 'Pos Counter 1',
                terminalLabel: 'T001'
            );

            $khqrResponse = BakongKHQR::generateMerchant($merchantInfo);

            $qrString = null;
            $md5Hash  = null;
            $data = $khqrResponse->data ?? ($khqrResponse->qr ?? null);
            
            if (is_array($data)) {
                $qrString = $data['qr'] ?? null;
                $md5Hash  = $data['md5'] ?? null;
            } elseif (is_object($data)) {
                $qrString = $data->qr ?? null;
                $md5Hash  = $data->md5 ?? null;
            }

            if (!$qrString && isset($khqrResponse->qr)) $qrString = $khqrResponse->qr;

            if (!$qrString) return response()->json(['message' => 'Failed to generate QR'], 500);

            $qrImage = QrCode::format('svg')->size(300)->margin(2)->color(225, 29, 72)->generate($qrString);

            return response()->json([
                'success'       => true,
                'qrString'      => $qrString,
                'qrImage'       => 'data:image/svg+xml;base64,' . base64_encode($qrImage),
                'md5'           => $md5Hash, 
                'amount'        => (float)$request->total,
                'invoice_number'=> $invoiceNumber
            ]);

        } catch (\Throwable $e) {
            Log::error('KHQR Failed: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function checkTransaction(Request $request) {
    $request->validate(['md5' => 'required|string', 'amount' => 'required|numeric']);

    try {
        // Optimized: Lower timeout. Better to fail fast and retry 
        // than to hang the server for 60 seconds.
        $response = Http::withToken($this->bakongToken)
            ->withOptions([
                'verify' => false, 
                'connect_timeout' => 5, // Fast connection
                'timeout' => 5         // Fast response
            ])
            ->post("{$this->bakongBaseUrl}/v1/check_transaction_by_md5", [
                'md5' => $request->md5,
                'merchantId' => $this->myMerchantId
            ]);

        if ($response->successful()) {
            $result = $response->json();
            
            // responseCode 0 means Success in Bakong API
            if (isset($result['responseCode']) && $result['responseCode'] === 0) {
                $data = $result['data'] ?? null;
                if ($data && abs((float)$data['amount'] - (float)$request->amount) < 0.01) {
                    return response()->json(['status' => 'PAID', 'data' => $data]);
                }
            }
        }
        
        return response()->json(['status' => 'PENDING']);
    } catch (\Exception $e) {
        // Log only critical errors to keep logs clean
        return response()->json(['status' => 'PENDING', 'message' => 'Retry polling...']);
    }
}

    public function pushToMobile(Request $request) {
        $request->validate([
            'terminal_id' => 'required|string',
            'action' => 'required|string', 
            'data' => 'nullable|array'
        ]);

        $key = 'pos_mob_cmd_' . $request->terminal_id;
        Cache::put($key, [
            'action' => $request->action,
            'data' => $request->data
        ], 15);

        return response()->json(['status' => 'sent']);
    }

    public function pollFromMobile(Request $request) {
        $request->validate(['terminal_id' => 'required|string']);

        $key = 'pos_mob_cmd_' . $request->terminal_id;
        $command = Cache::get($key);

        if ($command) {
            Cache::forget($key);
            return response()->json(['command' => $command]);
        }

        return response()->json(['command' => null]);
    }

    // In App\Http\Controllers\PosController.php

public function store(Request $request) {
    $data = $request->validate([
        'cart' => 'required|array',
        'payment_type' => 'required|string',
        'total' => 'numeric',
        'invoice_number' => 'nullable|string'
    ]);

    $invNum = $data['invoice_number'] ?? ('INV-' . strtoupper(uniqid()));

    // 1. ATOMIC LOCK: Prevent double submission for 10 seconds
    $lock = Cache::lock('processing_payment_' . $invNum, 10);

    if (!$lock->get()) {
        // If lock exists, it means payment is already processing!
        return response()->json(['success' => true, 'invoice' => $invNum, 'message' => 'Already processing']);
    }

    try {
        // 2. CHECK DATABASE: Ensure invoice doesn't exist yet
        if(Sale::where('invoice_number', $invNum)->exists()) {
            $lock->release();
            return response()->json(['success' => true, 'invoice' => $invNum]);
        }

        return DB::transaction(function () use ($data, $invNum, $lock) {
            $finalTotal = (float)$data['total'];
            
            $sale = Sale::create([
                'invoice_number' => $invNum,
                'payment_type' => $data['payment_type'],
                'total_amount' => $finalTotal,
                'tax' => 0,
                'final_total' => $finalTotal,
                'user_id' => auth()->id() ?? 1,
                'created_at' => Carbon::now('Asia/Phnom_Penh'),
                'updated_at' => Carbon::now('Asia/Phnom_Penh'),
            ]);

            foreach ($data['cart'] as $item) {
                // LOCK FOR UPDATE to handle stock concurrency
                $product = Product::lockForUpdate()->find($item['id']);

                if (!$product) throw new \Exception("Product {$item['name']} not found.");
                if ($product->qty < $item['qty']) throw new \Exception("Insufficient stock for {$product->name}.");
                
                $product->decrement('qty', $item['qty']);

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price']
                ]);
            }

            // Release lock after success
            $lock->release();

            return response()->json(['success' => true, 'invoice' => $sale->invoice_number]);
        });

    } catch (\Exception $e) {
        $lock->release(); // Release lock on error
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function getStock() {
        // Return only ID and Qty to keep it fast
        $stock = Product::select('id', 'qty')->get();
        return response()->json($stock);
    }
}