<?php

namespace App\Exports;

use App\Supplier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SupplierExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $supplier = Supplier::with('user')->orderBy('date', 'DESC')->get();
        return view('exports.supplier', [
            'supplier' => $supplier,
        ]);
    }
}
