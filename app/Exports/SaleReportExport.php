<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sale;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaleReportExport implements FromView
{
    protected $date_start;
    protected $date_end;
    protected $cards;
    public function __construct($date_start, $date_end)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        // dd($date_end);
    }

    public function view(): View
    {
        $cards = Sale::select([
            DB::raw("to_char(created_at, 'YYYY-mm') as month"),
            DB::raw("SUM(total_hpp) as total_hpp"),
            DB::raw("SUM(total_price) as total_price"),
            DB::raw("SUM(total_vatprice) as total_vatprice")
        ])->whereBetween('created_at', [$this->date_start, $this->date_end])->groupBy('month')->orderByRaw('max(created_at) asc')->get();

        $saleDetail = DB::table('sale_details')->selectRaw('menu_name, SUM(quantity) as count')->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->groupBy('menu_name')->get();
        $total = $cards->sum('total_vatprice');
        $saleCash = Sale::select([
            DB::raw("to_char(created_at, 'YYYY') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice")
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'cash')->groupBy('month')->orderByRaw('max(created_at) asc')->get();
        $total_cash = $saleCash->sum('total_vatprice');

        $saleBank = Sale::select([
            DB::raw("to_char(created_at, 'YYYY') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice")
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'bank_transfer')->groupBy('month')->orderByRaw('max(created_at) asc')->get();
        $total_bank = $saleBank->sum('total_vatprice');

        $saleCard = Sale::select([
            DB::raw("to_char(created_at, 'YYYY') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice")
        ])->whereBetween(DB::raw('DATE(created_at)'), [$this->date_start, $this->date_end])->where('payment_type', 'payment_card')->groupBy('month')->orderByRaw('max(created_at) asc')->get();
        $total_card = $saleCard->sum('total_vatprice');

        return view('exports.salereport', [
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'cards' => $cards,
            'total' => $total,
            'saleDetail' => $saleDetail,
            'saleCash' => $saleCash,
            'saleBank' => $saleBank,
            'saleCard' => $saleCard,
            'total_cash' => $total_cash,
            'total_bank' => $total_bank,
            'total_card' => $total_card
        ]);
    }
}
