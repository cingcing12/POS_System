<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sales Report - {{ $dateTitle }}</title>
    <style>
        @page { margin: 0px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 40px;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        
        /* Brand Colors */
        .text-primary { color: #1e3a8a; } /* Dark Blue */
        .text-secondary { color: #64748b; } /* Slate Gray */
        .text-success { color: #059669; } /* Emerald */
        .bg-light { background-color: #f8fafc; }
        
        /* Layout Helpers */
        .w-full { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        /* HEADER SECTION */
        .header-table { width: 100%; border-bottom: 2px solid #1e3a8a; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 28px; font-weight: 900; letter-spacing: -1px; color: #1e3a8a; }
        .company-info { font-size: 10px; color: #64748b; margin-top: 5px; }
        .report-title { font-size: 18px; font-weight: bold; color: #0f172a; text-align: right; }
        .report-meta { font-size: 10px; color: #64748b; text-align: right; margin-top: 5px; }

        /* SUMMARY CARDS (Using Table for PDF compatibility) */
        .summary-table { width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 10px 0; }
        .summary-card { 
            background-color: #f1f5f9; 
            border: 1px solid #e2e8f0; 
            padding: 15px; 
            border-radius: 8px;
            text-align: center;
        }
        .summary-label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 5px; }
        .summary-value { font-size: 18px; font-weight: bold; color: #1e3a8a; }

        /* DATA TABLE */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th { 
            background-color: #1e3a8a; 
            color: #ffffff; 
            text-align: left; 
            padding: 10px; 
            font-size: 10px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        .data-table td { 
            border-bottom: 1px solid #e2e8f0; 
            padding: 10px; 
            vertical-align: middle;
        }
        .data-table tr:nth-child(even) { background-color: #f8fafc; }
        .data-table tr:last-child td { border-bottom: 2px solid #1e3a8a; }

        /* BADGES */
        .badge { 
            padding: 3px 8px; 
            border-radius: 12px; 
            font-size: 9px; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .badge-cash { background-color: #dcfce7; color: #166534; }
        .badge-card { background-color: #dbeafe; color: #1e40af; }
        .badge-qr { background-color: #f3e8ff; color: #6b21a8; }

        /* FOOTER */
        .footer { 
            position: fixed; 
            bottom: 0; 
            left: 40px; 
            right: 40px; 
            height: 40px; 
            border-top: 1px solid #e2e8f0; 
            padding-top: 10px;
            font-size: 9px; 
            color: #94a3b8; 
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td valign="top">
                <div class="logo">POS CORP</div>
                <div class="company-info">
                    123 Business Street, Phnom Penh<br>
                    support@poscorp.com | +855 12 345 678
                </div>
            </td>
            <td valign="top">
                <div class="report-title">SALES REPORT</div>
                <div class="report-meta">
                    <strong>Period:</strong> {{ $dateTitle }}<br>
                    <strong>Generated:</strong> {{ date('d M Y, h:i A') }}<br>
                    <strong>By:</strong> {{ auth()->user()->name ?? 'Admin' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td width="33%">
                <div class="summary-card">
                    <div class="summary-label">Total Revenue</div>
                    <div class="summary-value">${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </td>
            <td width="33%">
                <div class="summary-card">
                    <div class="summary-label">Transactions</div>
                    <div class="summary-value">{{ $totalCount }}</div>
                </div>
            </td>
            <td width="33%">
                @php $avg = $totalCount > 0 ? $totalRevenue / $totalCount : 0; @endphp
                <div class="summary-card">
                    <div class="summary-label">Avg. Ticket</div>
                    <div class="summary-value">${{ number_format($avg, 2) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Invoice</th>
                <th width="15%">Time</th>
                <th width="15%">Staff</th>
                <th width="30%">Items</th>
                <th width="10%">Payment</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>
                    <span class="font-bold text-primary">#{{ $sale->invoice_number }}</span>
                </td>
                <td class="text-secondary">
                    {{ $sale->created_at->format('M d, H:i') }}
                </td>
                <td>
                    {{ $sale->user->name ?? 'System' }}
                </td>
                <td style="color: #475569;">
                    @foreach($sale->details->take(2) as $item)
                        <div>• {{ $item->product->name ?? 'Unknown' }} <span style="color:#94a3b8; font-size:9px;">(x{{ $item->qty }})</span></div>
                    @endforeach
                    @if($sale->details->count() > 2)
                        <div style="font-style: italic; color:#94a3b8; margin-top:2px;">+ {{ $sale->details->count() - 2 }} more...</div>
                    @endif
                </td>
                <td>
                    @php 
                        $type = strtolower($sale->payment_type);
                        $badgeClass = 'badge-cash';
                        if(strpos($type, 'card') !== false) $badgeClass = 'badge-card';
                        if(strpos($type, 'qr') !== false) $badgeClass = 'badge-qr';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $sale->payment_type }}</span>
                </td>
                <td class="text-right">
                    <div class="font-bold">${{ number_format($sale->final_total, 2) }}</div>
                    @if($sale->discount > 0)
                        <div style="color: #ef4444; font-size: 9px;">-${{ number_format($sale->discount, 2) }}</div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page <span class="page-number"></span> | Confidential Report generated by POS System | © {{ date('Y') }} POS Corp
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Helvetica", "normal");
                $size = 9;
                $pageText = $PAGE_NUM . " / " . $PAGE_COUNT;
                $y = 800; // Vertical position of page number
                $x = 520; // Horizontal position
                $pdf->text($x, $y, $pageText, $font, $size, array(0.5, 0.5, 0.5));
            ');
        }
    </script>

</body>
</html>