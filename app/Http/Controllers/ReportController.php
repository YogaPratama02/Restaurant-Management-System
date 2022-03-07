<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseReportExport;
use App\Exports\SaleDayExport;
use App\Exports\SaleReportExport;
use App\Sale;
use App\Supplier;
use App\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
            $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
            // $hmtl = array();
            $html['cash'] = '';
            $html['transfer'] = '';
            $html['credit'] = '';

            $sale_cash = DB::table('sales')->select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month,
                SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt'),
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'cash')->groupBy('month')->orderBy('createdAt')->get();
            foreach ($sale_cash as $sale) {
                $html['cash'] .= 'Rp. ' . number_format($sale->total_vatprice, 0, ',', '.');
            }

            $saleBank = DB::table('sales')->select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month,
                SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt'),
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'bank_transfer')->groupBy('month')->orderBy('createdAt')->get();

            foreach ($saleBank as $saleBank) {
                $html['transfer'] .= 'Rp. ' . number_format($saleBank->total_vatprice, 0, ',', '.');
            }

            $saleCard = DB::table('sales')->select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month,
                SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt'),
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'payment_card')->groupBy('month')->orderBy('createdAt')->get();

            foreach ($saleCard as $saleCard) {
                $html['credit'] .= 'Rp. ' . number_format($saleCard->total_vatprice, 0, ',', '.');
            }
            return json_encode($html);
        }

        return view('report.index');
    }

    public function dataDailyCustomers(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        // $user = Sale::whereBetween('created_at', [$date_start, $date_end])->orderBy('created_at', 'asc')->get();
        $user = Sale::whereBetween(DB::raw('DATE(created_at)'), array($date_start, $date_end))->orderBy('created_at', 'asc')->get();
        return DataTables()->of($user)
            ->addColumn('created_at', function ($user) {
                $date = date("d M Y", strtotime($user->created_at));
                return $date;
            })
            ->addColumn('table_id', function ($user) {
                return $user->table->name;
            })
            ->addColumn('total_vatprice', function ($user) {
                $total_vatprice = 'Rp. ';
                $total_vatprice .= number_format($user->total_vatprice, 0, ',', '.');
                return $total_vatprice;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['created_at', 'table_id', 'total_vatprice'])
            ->make(true);
    }

    public function deleteDataDaily($id)
    {
        Sale::findOrFail($id)->delete();
        return response()->json();
    }

    public function dataDaily(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        $sale = DB::table('sales')->select(
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as date,
            SUM(total_hpp) as total_hpp,
            SUM(total_price) as total_price,
            SUM(total_vatprice) as total_vatprice"),
            DB::raw('max(created_at) as createdAt')
        )->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('date')->orderBy('createdAt')->get();
        return DataTables()->of($sale)
            ->addColumn('date', function ($sale) {
                $date = date("d M Y", strtotime($sale->date));
                return $date;
            })
            ->addColumn('total_hpp', function ($sale) {
                $total_hpp = 'Rp. ';
                $total_hpp .= number_format($sale->total_hpp, 0, ',', '.');
                return $total_hpp;
            })
            ->addColumn('total_price', function ($sale) {
                $total_price = 'Rp. ';
                $total_price .= number_format($sale->total_price, 0, ',', '.');
                return $total_price;
            })
            ->addColumn('total_vatprice', function ($sale) {
                $total_vatprice = 'Rp. ';
                $total_vatprice .= number_format($sale->total_vatprice, 0, ',', '.');
                return $total_vatprice;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['date', 'total_hpp', 'total_price', 'total_vatprice', 'action'])
            ->make(true);
    }

    public function typeDaily(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        $sale = DB::table('sales')->select([
            DB::raw(
                "DATE_FORMAT(created_at, '%Y') as month,
            (CASE WHEN payment_type = 'cash' THEN SUM(total_vatprice) WHEN payment_type = 'bank_transfer' THEN SUM(total_vatprice) WHEN payment_type = 'payment_card' THEN SUM(total_vatprice) END) as total_cash"
            ),
            DB::raw('max(created_at) as createdAt'),
        ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->groupBy('payment_type')->orderBy('createdAt')->get();
        return DataTables()->of($sale)
            ->addColumn('cash', function ($sale) {
                $price = 'Rp. ';
                $price .= number_format($sale->total_cash, 0, ',', '.');
                return $price;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['cash'])
            ->make(true);
    }

    public function resumeDaily(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        $sale_detail = DB::table('sale_details')->selectRaw('menu_name, SUM(quantity) as count')->groupBy('menu_name')->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_name')->get();
        return DataTables()->of($sale_detail)
            ->addColumn('menu_name', function ($sale_detail) {
                return $sale_detail->menu_name;
            })
            ->addColumn('count', function ($sale_detail) {
                return $sale_detail->count;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['menu_name', 'count'])
            ->make(true);
    }

    public function dayExcel(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        return Excel::download(new SaleDayExport($date_start, $date_end), 'saleReport.xlsx');
    }

    public function month(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
            $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
            // $date_start = date("Y-m-d H:i:s", strtotime($request->date_start));
            // $date_end = date("Y-m-d H:i:s", strtotime($request->date_end));
            $html['cash'] = '';
            $html['bank'] = '';
            $html['card'] = '';

            $sale_cash = DB::table('sales')->select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as year,
                        SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt'),
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'cash')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))->orderBy('createdAt')->get();
            $total_cash = $sale_cash->sum('total_vatprice');

            // foreach ($sale_cash as $sale) {
            $html['cash'] .= 'Rp. ' . number_format($total_cash, 0, ',', '.');
            // }
            // dd($html['cash']);

            $sale_bank = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month,
                SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt'),
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'bank_transfer')->groupBy('month')->orderBy('createdAt')->get();
            $total_bank = $sale_bank->sum('total_vatprice');

            $html['bank'] .= 'Rp. ' . number_format($total_bank, 0, ',', '.');

            $sale_card = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month,
                SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt'),
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'payment_card')->groupBy('month')->orderBy('createdAt')->get();
            $total_card = $sale_card->sum('total_vatprice');

            $html['card'] .= 'Rp. ' . number_format($total_card, 0, ',', '.');
            return json_encode($html);
        }
        // Graphic
        $count_month = DB::table('sales')->select([
            DB::raw("DATE_FORMAT(created_at, '%m-%Y') as month,
            SUM(total_vatprice) as total_vatprice"),
            DB::raw('max(created_at) as createdAt'),
        ])->groupBy('month')->orderBy('createdAt')->get();
        $month_name = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];
        $bulan = [];
        $data = [];
        foreach ($count_month as $count_month) {
            $month = $count_month->month;
            $month_number = explode("-", $month)[0];
            $year = explode("-", $month)[1];
            array_push($bulan, $month_name[$month_number - 1] . ' ' . $year);
            array_push($data, $count_month->total_vatprice);
        }
        // dd($data);
        return view('report.indexmonth', ['month' => json_encode($bulan), 'data' => json_encode($data)]);
    }

    public function dataMonth(Request $request)
    {
        // $date_start = date("Y-m-d H:i:s", strtotime($request->date_start));
        // $date_end = date("Y-m-d H:i:s", strtotime($request->date_end));
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        $sale = DB::table('sales')->select([
            DB::raw(
                "DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total_hpp) as total_hpp,
                SUM(total_price) as total_price,
                SUM(total_vatprice) as total_vatprice"
            ),
            DB::raw('max(created_at) as createdAt'),
        ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderBy('createdAt')->get();
        return DataTables()->of($sale)
            ->addColumn('date', function ($sale) {
                $date = date("M Y", strtotime($sale->month));
                return $date;
            })
            ->addColumn('total_hpp', function ($sale) {
                $total_hpp = 'Rp. ';
                $total_hpp .= number_format($sale->total_hpp, 0, ',', '.');
                return $total_hpp;
            })
            ->addColumn('total_price', function ($sale) {
                $total_price = 'Rp. ';
                $total_price .= number_format($sale->total_price, 0, ',', '.');
                return $total_price;
            })
            ->addColumn('total_vatprice', function ($sale) {
                $total_vatprice = 'Rp. ';
                $total_vatprice .= number_format($sale->total_vatprice, 0, ',', '.');
                return $total_vatprice;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['date', 'total_hpp', 'total_price', 'total_vatprice'])
            ->make(true);
    }

    public function menuMonth(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        $sale_detail = DB::table('sale_details')->selectRaw('menu_name, SUM(quantity) as count')->groupBy('menu_name')->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_name')->get();
        return DataTables()->of($sale_detail)
            ->addColumn('menu_name', function ($sale_detail) {
                return $sale_detail->menu_name;
            })
            ->addColumn('count', function ($sale_detail) {
                return $sale_detail->count;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['menu_name', 'count'])
            ->make(true);
    }

    public function reportExcel(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        return Excel::download(new SaleReportExport($date_start, $date_end), 'saleReport.xlsx');
    }

    public function indexEmployee()
    {
        return view('report.employee');
    }
    public function employee(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));

        // $sales = Sale::select(DB::raw('count(user_id) as count, user_id'))->groupBy('user_id')->whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid')->where(function ($sale) {
        //     $sale->whereHas('user', function ($sale) {
        //         return $sale->role('cashier');
        //     });
        // })->get();

        $sales = DB::table('sales')->join('users', 'users.id', '=', 'sales.user_id')->select(
            'users.id as id',
            'users.name as name',
            DB::raw("count(users.name) as count")
        )->groupBy('users.id')->whereBetween(DB::raw('DATE(sales.created_at)'), [$date_start, $date_end])
            ->where('sales.sale_status', 'paid')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'cashier')
            ->get();
        // $sales = Auth::user()->Role('cashiers')->whereBetween(DB::raw('DATE(sales.created_at)'), [$date_start, $date_end]);
        return DataTables()->of($sales)
            ->addColumn('employee_name', function ($sales) {
                return $sales->name;
            })
            ->addColumn('count', function ($sales) {
                return $sales->count;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['employee_name', 'count'])
            ->make(true);
    }

    public function indexPurchase()
    {
        return view('report.purchase');
    }

    public function purchase(Request $request)
    {
        $date_start = date("Y-m-d 0:0:0", strtotime($request->date_start));
        $date_end = date("Y-m-d 23:59:59", strtotime($request->date_end));
        $purchase = Supplier::select([
            DB::raw("DATE_FORMAT(date, '%Y-%m') as month"),
            DB::raw("SUM(total) as total"),
            DB::raw('max(date) as createdAt'),
        ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderBy('createdAt')->get();
        return DataTables()->of($purchase)
            ->addColumn('month', function ($purchase) {
                return date("M Y", strtotime($purchase->month));
            })
            ->addColumn('total', function ($purchase) {
                $total = 'Rp. ';
                $total .= number_format($purchase->total, 0, ',', '.');
                return $total;
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns(['month', 'total'])
            ->make(true);

        // $purc = Supplier::select([
        //     DB::raw("to_char(created_at, 'YYYY') as month"),
        //     DB::raw("SUM(total) as total")
        // ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderByRaw('max(created_at) asc')->get();

        // $detail = Supplier::select(DB::raw('count(name) as count, name'))->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('name')->get();
    }

    //     $count_month = Sale::select([
    //         DB::raw("DATE_FORMAT(created_at, '%m-%Y') as month"),
    //         DB::raw("SUM(total_vatprice) as total_vatprice"),
    //         DB::raw('max(created_at) as createdAt')
    //     ])->groupBy('month')->orderBy('createdAt')->get();
    //     $month_name = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];
    //     $bulan = [];
    //     $data = [];
    //     foreach ($count_month as $count_month) {
    //         $month = $count_month->month;
    //         $month_number = explode("-", $month)[0];
    //         $year = explode("-", $month)[1];
    //         array_push($bulan, $month_name[$month_number - 1] . ' ' . $year);
    //         array_push($data, $count_month->total_vatprice);
    //     }
    //     // $formatted_array = array_map(function ($num) {
    //     //     return number_format($num, 2, ',', '.');
    //     // }, $data);

    //     return view('report.indexmonth', ['month' => json_encode($bulan), 'data' => json_encode($data)]);
    // }

    public function reportExcelPurchase(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        return Excel::download(new PurchaseReportExport($date_start, $date_end), 'purchaseReport.xlsx');
    }

    public function customers(Request $request)
    {
        if ($request->ajax()) {
            $data = Sale::where('customer_phone', '!=', '')->get();
            return DataTables()->of($data)
                ->addColumn('customer_name', function ($data) {
                    return $data->customer_name;
                })
                ->addColumn('customer_phone', function ($data) {
                    return $data->customer_phone;
                })
                ->addIndexColumn()
                ->rawColumns(['customer_name', 'customer_phone'])
                ->make(true);
        }
        return view('report.customer');
    }

    public function member(Request $request)
    {
        if ($request->ajax()) {
            // $member = DB::table('users')->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')->select(
            //     'users.id as id',
            //     'users.name as name',
            //     'users.email as email'
            // )->groupBy('users.id')->join('roles', 'model_has_roles.role_id', '=', 'roles.id')->where('roles.name', '=', 'members')->get();
            $member = Auth::user()->Role('members');

            return DataTables()->of($member)
                ->addColumn('name', function ($member) {
                    return $member->name;
                })
                ->addColumn('email', function ($member) {
                    return $member->email;
                })
                ->addIndexColumn()
                ->rawColumns(['name', 'email'])
                ->make(true);
        }
        return view('report.member');
    }
}
