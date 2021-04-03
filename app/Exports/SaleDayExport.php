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
        // dd($date_end);
    }

    public function view(): View
    {
        $sale = Sale::select([
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as date"),
            DB::raw("SUM(total_hpp) as total_hpp"),
            DB::raw("SUM(total_price) as total_price"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
            DB::raw('max(created_at) as createdAt')
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->groupBy('date')->orderBy('createdAt')->get();
        $total = $sale->sum('total_vatprice');

        $saleCash = Sale::select([
            DB::raw("DATE_FORMAT(created_at, '%m') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
            DB::raw('max(created_at) as createdAt')
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'cash')->groupBy('month')->orderBy('createdAt')->get();

        $saleBank = Sale::select([
            DB::raw("DATE_FORMAT(created_at, '%m') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
            DB::raw('max(created_at) as createdAt')
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'bank transfer')->groupBy('month')->orderBy('createdAt')->get();

        $saleCredit = Sale::select([
            DB::raw("DATE_FORMAT(created_at, '%m') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
            DB::raw('max(created_at) as createdAt')
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'payment Card')->groupBy('month')->orderBy('createdAt')->get();

        $saleDetail = SaleDetail::select(DB::raw('count(menu_name) as count, menu_name'))->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->groupBy('menu_name')->get();

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
