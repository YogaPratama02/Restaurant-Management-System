<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sale;
use App\SaleDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class SaleDayExport implements FromView
{
    protected $date_start;
    protected $date_end;
    protected $sale;
    public function __construct($date_start, $date_end)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
    }

    public function view(): View
    {
        $sale = Sale::select([
            DB::raw("to_char(created_at, 'dd-mm-YYYY') as date"),
            DB::raw("SUM(total_hpp) as total_hpp"),
            DB::raw("SUM(total_price) as total_price"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->groupBy('date')->orderByRaw('max(created_at) asc')->get();
        $total = $sale->sum('total_vatprice');

        $saleCash = Sale::select([
            DB::raw("to_char(created_at, 'mm') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'cash')->groupBy('month')->orderByRaw('max(created_at) asc')->get();

        $saleBank = Sale::select([
            DB::raw("to_char(created_at, 'mm') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'bank_transfer')->groupBy('month')->orderByRaw('max(created_at) asc')->get();

        $saleCredit = Sale::select([
            DB::raw("to_char(created_at, 'mm') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'payment_card')->groupBy('month')->orderByRaw('max(created_at) asc')->get();

        $saleDetail = DB::table('sale_details')->selectRaw('menu_name, SUM(quantity) as count')->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->groupBy('menu_name')->get();

        return view('exports.dayreport', [
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'sale' => $sale,
            'total' => $total,
            'saleDetail' => $saleDetail,
            'saleCash' => $saleCash,
            'saleBank' => $saleBank,
            'saleCredit' => $saleCredit
        ]);
    }
}
