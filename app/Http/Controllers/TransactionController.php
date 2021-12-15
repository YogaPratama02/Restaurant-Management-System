<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        return view('transaction.index');
    }

    public function delete($id)
    {
        Sale::findOrFail($id)->delete();
        return response()->json();
    }

    public function data(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        $model = Sale::where('sale_status', 'paid');
        if ($request->date_start != '') {
            $model->whereBetween('created_at', [$date_start, $date_end])->orderBy('created_at', 'ASC');
        } else {
            $model->whereDate('created_at', Carbon::today())->orderBy('created_at', 'ASC');
        }
        $model = $model->get();

        return DataTables::of($model)
            ->addColumn('created_at', function ($model) {
                $date = date("d M Y", strtotime($model->created_at));
                return $date;
            })
            ->addColumn('total_hpp', function ($model) {
                $result = 'Rp ';
                $result .= number_format($model->total_hpp, 0, ',', '.');
                return $result;
            })
            ->addColumn('total_price', function ($model) {
                $result = 'Rp ';
                $result .= number_format($model->total_price, 0, ',', '.');
                return $result;
            })
            ->addColumn('total_vatprice', function ($model) {
                $result = 'Rp ';
                $result .= number_format($model->total_vatprice, 0, ',', '.');
                return $result;
            })
            ->addColumn('action', function ($model) {
                return '<div class="btn-group" role="group">
                        <button type="button" href="' . route('transaction.delete', $model->id) . '" class="btn btn-danger btn-delete delete" name="Delete">Delete</button>
                    </div>';
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['action', 'created_at', 'total_hpp', 'total_price', 'total_vatprice'])
            ->make(true);
    }
}
