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
