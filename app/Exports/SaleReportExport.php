<?php

namespace App\Exports;

use App\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaleReportExport implements FromView
{
    private $date_start;
    private $date_end;
    private $sales;
    private $totalSale;
    public function __construct($date_start, $date_end)
    {
        $date_start = date("Y-m-d H:i:s", strtotime($date_start));
        $date_end = date("Y-m-d H:i:s", strtotime($date_end));

        $sales = Sale::whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid')->get();
        $totalSale = $sales->sum('total_price');
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->sales = $sales;
        $this->totalSale = $totalSale;
    }

    public function view(): View
    {
        return view('exports.salereport', [
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'sales' => $this->sales,
            'totalSale' => $this->totalSale
        ]);
    }
}
