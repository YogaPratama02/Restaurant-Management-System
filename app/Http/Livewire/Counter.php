<?php

namespace App\Http\Livewire;

use App\Sale;
use App\SaleDetail;
use Livewire\Component;

class Counter extends Component
{
    // public $count = 'haha';
    // public function increment()
    // {
    //     $this->count;
    // }

    // public function decrement()
    // {
    //     $this->count--;
    // }
    // public $data;

    public function render()
    {
        $sale = Sale::where('sale_status', 'unpaid')->where(function ($sale) {
            $sale->whereHas('saleDetails', function ($sale) {
                return $sale->where('status', 'confirm');
            });
        })->get();

        // $sale = Sale::whereHas('saleDetails', function ($query) {
        //     $query->where('status', 'confirm');
        // })->get();

        $saleDetail = SaleDetail::where('status', 'confirm')->where(function ($saleDetail) {
            $saleDetail->whereHas('sale', function ($saleDetail) {
                return $saleDetail->where('sale_status', 'unpaid');
            });
        })->get();
        // $saleDetail = saleDetail::get();
        // dd($saleDetail);
        return view('livewire.counter', ['sale' => $sale, 'saleDetails' => $saleDetail]);
        // return view('livewire.counter');
    }
}
