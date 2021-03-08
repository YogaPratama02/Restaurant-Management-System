<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\SaleDetail;
use App\Supplier;
use App\Inventory;
use App\User;


use Illuminate\Support\Facades\DB;
use App\Exports\SaleReportExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start . ' 00:00:00'));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end . ' 23:59:59'));
            $sales = Sale::whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid');
            return DataTables()->of($sales)
                ->editColumn('updated_at', function ($sales) {
                    $date = date('d M Y', strtotime($sales->updated_at));
                    return $date;
                })
                ->addColumn('total_hpp', function ($sales) {
                    $total_hpp = 'Rp. ';
                    $total_hpp .= number_format($sales->total_hpp, 0, ',', '.');
                    return $total_hpp;
                })
                ->addColumn('user_name', function ($sales) {
                    return $sales->user_name;
                })
                ->addColumn('total_price', function ($sales) {
                    $total_price = 'Rp. ';
                    $total_price .= number_format($sales->total_price, 0, ',', '.');
                    return $total_price;
                })
                ->addColumn('total_vat', function ($sales) {
                    return "$sales->total_vat %";
                })
                ->addColumn('total_vatprice', function ($sales) {
                    $total_price = 'Rp. ';
                    $total_price .= number_format($sales->total_vatprice, 0, ',', '.');
                    return $total_price;
                })
                ->addColumn('payment_type', function ($sales) {
                    return $sales->payment_type;
                })
                ->addColumn('action', function ($sales) {
                    $button = '<a href="' . route('report.detail', $sales->id) . '" style="cursor: pointer;" class="btn-show view-detail"><i class="fas fa-eye" style="color: black;"></i></a>';
                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['updated_at', 'total_hpp', 'user_name', 'total_price', 'total_vat', 'total_vatprice', 'payment_type', 'action'])
                ->make(true);
        }

        $date_start = date("Y-m-d H:i:s", strtotime($request->date_start . ' 00:00:00'));
        $date_end = date("Y-m-d H:i:s", strtotime($request->date_end . ' 23:59:59'));
        $sales = Sale::whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid');
        return view('report.index')->with('date_start', date("d/m/Y H:i:s", strtotime($request->date_start . ' 00:00:00')))->with('date_end', date('d/m/Y H:i:s', strtotime($request->date_end . ' 23:59:59')))->with('total', $sales->sum('total_hpp'))->with('total_price', $sales->sum('total_price'))->with('sales', $sales);
    }

    public function resume(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start . ' 00:00:00'));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end . ' 23:59:59'));
            $sales = Sale::whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid')->get();
            $html['hpp'] = 0;
            $html['price'] = 0;
            $html['vatprice'] = 0;
            $html['cash'] = 0;
            $html['bank'] = 0;
            $html['menus'] = '';

            $sale_payment = Sale::whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid')->where('payment_type', 'cash')->get();
            foreach ($sale_payment as $payment) {
                $html['cash'] += $payment->total_vatprice;
            }

            $sale_bank = Sale::whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid')->where('payment_type', 'bank transfer')->get();

            foreach ($sale_bank as $bank) {
                $html['bank'] += $bank->total_vatprice;
            }

            foreach ($sales as $sale) {
                $html['hpp'] += $sale->total_hpp;
                $html['price'] += $sale->total_price;
                $html['vatprice'] += $sale->total_vatprice;
            }
            $html['hpp'] = number_format($html['hpp'], 0, ',', '.');
            $html['price'] = number_format($html['price'], 0, ',', '.');
            $html['vatprice'] = number_format($html['vatprice'], 0, ',', '.');
            $html['cash'] = number_format($html['cash'], 0, ',', '.');
            $html['bank'] = number_format($html['bank'], 0, ',', '.');

            $sale_detail = SaleDetail::select(DB::raw('count(menu_name) as count, menu_name'))->groupBy('menu_name')->get();
            foreach ($sale_detail as $sale_detail) {
                $html['menus'] .= '<tr>
                    <td>' . $sale_detail->menu_name . '</td>
                    <td>' . $sale_detail->count . '</td>
                </tr>';
            }

            return json_encode($html);
        }
    }

    public function detail($id)
    {
        $sales = Sale::find($id);
        $saleDetail = SaleDetail::where('sale_id', $sales->id)->get();
        return view('report.show')->with('saleDetail', $saleDetail);
    }

    public function reportExcel(Request $request)
    {
        if ($request->ajax()) {
            return Excel::download(new SaleReportExport($request->date_start, $request->date_end), 'saleReport.xlsx');
        }
    }

    public function month(Request $request)
    {
        return view('report.indexmonth');
    }

    public function employee(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start . ' 00:00:00'));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end . ' 23:59:59'));
            $sales = Sale::select(DB::raw('count(user_name) as count, user_name'))->groupBy('user_name')->whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid')->where(function ($sale) {
                $sale->whereHas('user', function ($sale) {
                    return $sale->where('role', 'cashier');
                });
            })->get();
            // $html = array();
            $html = '';
            foreach ($sales as $sale) {
                $html .= '<tr>
                    <td>' . $sale->user_name . '</td>
                    <td>' . $sale->count . '</td>
                </tr>';
            }
            return json_encode($html);
        }
        return view('report.employee');
    }

    public function purchase(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start . ' 00:00:00'));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end . ' 23:59:59'));
            $purchase = Supplier::whereBetween('updated_at', [$date_start, $date_end]);
            return DataTables()->of($purchase)
                ->editColumn('date', function ($purchase) {
                    $date = date('d M Y', strtotime($purchase->date));
                    return $date;
                })
                ->addColumn('updated_at', function ($purchase) {
                    $input = date('d M Y', strtotime($purchase->updated_at));
                    return $input;
                })
                ->addColumn('name', function ($purchase) {
                    $name = $purchase->name;
                    return $name;
                })
                ->addColumn('total', function ($purchase) {
                    $total = 'Rp. ';
                    $total .= number_format($purchase->total, 0, ',', '.');
                    return $total;
                })
                ->addIndexColumn()
                ->rawColumns(['date', 'name', 'total', 'updated_at'])
                ->make(true);
        }
        return view('report.purchase');
    }

    public function purchaseTotal(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start . ' 00:00:00'));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end . ' 23:59:59'));
            $purchase = Supplier::whereBetween('updated_at', [$date_start, $date_end])->get();
            $html['total'] = 0;
            foreach ($purchase as $purchase) {
                $html['total'] += $purchase->total;
            }
            $html['total'] = number_format($html['total'], 0, ',', '.');
        }
        return json_encode($html);
    }
}



    // public function show(Request $request)
    // {
    //     $request->validate([
    //         'dateStart' => 'required',
    //         'dateEnd' => 'required'
    //     ]);
    //     $dateStart = date("Y-m-d H:i:s", strtotime($request->dateStart . ' 00:00:00'));
    //     $dateEnd = date("Y-m-d H:i:s", strtotime($request->dateEnd . ' 23:59:59'));

    //     $sales = Sale::whereBetween('updated_at', [$dateStart, $dateEnd])->where('sale_status', 'paid');
        // return view('report.showReport')->with('dateStart', date("d-m-Y H:i:s", strtotime($request->dateStart . ' 00:00:00')))->with('dateEnd', date("d-m-Y H:i:s", strtotime($request->dateEnd . ' 23:59:59')))->with('totalSale', $sales->sum('total_price'))->with('sales', $sales->paginate(5));
    // }



    // public function dataTable(Request $request)
    // {
    //     // $request->validate([
    //     //     'dateStart' => 'required',
    //     //     'dateEnd' => 'required'
    //     // ]);
    //     // $dateStart = date("Y-m-d H:i:s", strtotime($request->dateStart . ' 00:00:00'));
    //     // $dateEnd = date("Y-m-d H:i:s", strtotime($request->dateEnd . ' 23:59:59'));
    //     // $babi = Sale::whereBetween('updated_at', [$dateStart, $dateEnd])->where('sale_status', 'paid');
    //     // $sales('dateStart', date("d-m-Y H:i:s", strtotime($request->dateStart . ' 00:00:00')))->with('dateEnd', date("d-m-Y H:i:s", strtotime($request->dateEnd . ' 23:59:59')))->with('totalSale', $sales->sum('total_price'))->with('sales', $sales->paginate(5));

    //     // $saless = Sale::where('id', $sales->id)->get();
    //     // dd($saless);
    //     $sales = Sale::all();
    //     return DataTables()->of($sales)
    //         ->editColumn('updated_at', function ($sales) {
    //             $date = date('d M Y', strtotime($sales->updated_at));
    //             return $date;
    //             // return $sales->updated_at;
    //             // return $sales->updated_at;
    //         })
    //         ->addColumn('total_hpp', function ($sales) {
    //             $total_hpp = 'Rp. ';
    //             $total_hpp .= number_format($sales->total_hpp, 0, ',', '.');
    //             return $total_hpp;
    //         })
    //         ->addColumn('user_name', function ($sales) {
    //             return $sales->user_name;
    //         })
    //         ->addColumn('total_price', function ($sales) {
    //             $total_price = 'Rp. ';
    //             $total_price .= number_format($sales->total_price, 0, ',', '.');
    //             return $total_price;
    //         })
    //         ->addColumn('total_vat', function ($sales) {
    //             return "$sales->total_vat %";
    //         })
    //         ->addColumn('total_vatprice', function ($sales) {
    //             $total_price = 'Rp. ';
    //             $total_price .= number_format($sales->total_vatprice, 0, ',', '.');
    //             return $total_price;
    //         })
    //         ->addColumn('payment_type', function ($sales) {
    //             return $sales->payment_type;
    //         })
    //         ->addColumn('action', function ($sales) {
    //             $button = '<a href="' . route('report.detail', $sales->id) . '" style="cursor: pointer;" class="btn-show view-detail"><i class="fas fa-eye" style="color: black;"></i></a>';
    //             return $button;
    //         })
    //         ->addIndexColumn()
    //         ->rawColumns(['updated_at', 'total_hpp', 'user_name', 'total_price', 'total_vat', 'total_vatprice', 'payment_type', 'action'])
    //         ->make(true);
    // }



    // public function showreport(Request $request)
    // {
    //     $request->validate([
    //         'dateStart' => 'required',
    //         'dateEnd' => 'required'
    //     ]);
    //     $dateStart = date("Y-m-d H:i:s", strtotime($request->dateStart . ' 00:00:00'));
    //     $dateEnd = date("Y-m-d H:i:s", strtotime($request->dateEnd . ' 23:59:59'));

    //     $supplier = Supplier::whereBetween('updated_at', [$dateStart, $dateEnd]);
    //     return view('purchasereport.showReport')->with('dateStart', date("d-m-Y H:i:s", strtotime($request->dateStart . ' 00:00:00')))->with('dateEnd', date("d-m-Y H:i:s", strtotime($request->dateEnd . ' 23:59:59')))->with('total', $supplier->sum('total'))->with('supplier', $supplier->paginate(5));
    // }

    // public function purchaseData(){

    // }
// }
