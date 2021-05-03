<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voucher;
use Carbon\Carbon;
use DataTables;

class VoucherController extends Controller
{
    public function index()
    {
        return view('voucher.index');
    }

    public function create()
    {
        $voucher = new Voucher();
        return view('voucher.formvoucher', compact('voucher'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'discount' => 'required|numeric',
            'status' => 'required|max:255',
        ]);

        $voucher = new Voucher();
        $voucher->name = $request->name;
        $voucher->discount = $request->discount;
        $voucher->status = $request->status;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $voucher->created_at = $current;
        $voucher->updated_at = $current;
        $voucher->save();
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('voucher.formvoucher', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'discount' => 'required|numeric',
            'status' => 'required|max:255',
        ]);

        $voucher = Voucher::find($id);
        $voucher->name = $request->name;
        $voucher->discount = $request->discount;
        $voucher->status = $request->status;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $voucher->updated_at = $current;
        $voucher->save();
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
    }

    public function dataTable()
    {
        $voucher = Voucher::all();
        return DataTables()->of($voucher)
            ->addColumn('action', function ($voucher) {
                return view('voucher.voucheraction', [
                    'voucher' => $voucher,
                    'url_edit' => route('voucher.edit', $voucher->id),
                    'url_destroy' => route('voucher.destroy', $voucher->id)
                ]);
            })
            ->addColumn('name', function ($voucher) {
                return $voucher->name;
            })
            ->addColumn('discount', function ($voucher) {
                return "$voucher->discount %";
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'name', 'discount', 'status'])
            ->make(true);
    }
}
