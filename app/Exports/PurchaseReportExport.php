<?php

namespace App\Exports;

use App\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PurchaseReportExport implements FromView
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
        $purchase = Supplier::select([
            DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
            DB::raw("SUM(total) as total"),
            DB::raw('max(date) as date'),
        ])->whereBetween('date', [$this->date_start, $this->date_end])->groupBy('month')->orderBy('date')->get();

        return view('exports.purchase', [
            'purchase' => $purchase,
        ]);
    }
}
