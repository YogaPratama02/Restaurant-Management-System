<?php

namespace App\Http\Controllers;

use App\Sale;
use App\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenController extends Controller
{
    public function index()
    {
        // $saleDetail = SaleDetail::where('status', 'confirm')->where(function ($saleDetail) {
        //     $saleDetail->whereHas('sale', function ($saleDetail) {
        //         return $saleDetail->where('sale_status', 'unpaid');
        //     });
        // })->get();
        // $sale = Sale::where('sale_status', 'unpaid')->where(function ($sale) {
        //     $sale->whereHas('saleDetails', function ($sale) {
        //         return $sale->where('status', 'confirm');
        //     });
        // })->get();
        // dd($saleDetail);
        return view('kitchen.index');
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $saledetail = SaleDetail::find($request->id)->update(['status' => 'ready']);
        }
        return response()->json($saledetail);
    }
}
