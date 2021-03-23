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
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM(total_hpp) as total_hpp"),
            DB::raw("SUM(total_price) as total_price"),
            DB::raw("SUM(total_vatprice) as total_vatprice")
        ])->where('created_at', '>', $this->date_start)->where('created_at', '<', $this->date_end)->groupBy('month')->orderBy('month')->get();
        // dd($cards);
        return view('exports.salereport', [
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'cards' => $cards
        ]);
    }
}
