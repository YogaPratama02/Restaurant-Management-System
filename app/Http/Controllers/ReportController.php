<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\SaleDetail;
use App\Supplier;
use App\Inventory;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\SaleReportExport;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end));
            $sale = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as date"),
                DB::raw("SUM(total_hpp) as total_hpp"),
                DB::raw("SUM(total_price) as total_price"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('date')->orderBy('createdAt')->get();
            // $hmtl = array();
            $html['a'] = '';
            $html['day'] = '';
            $i = 0;
            foreach ($sale as $sale) {
                $html['a'] .= '<tr>
                    <td>' . ++$i . '</td>
                    <td>' . date("d M Y", strtotime($sale->date)) . '</td>
                    <td>' . 'Rp. ' . number_format($sale->total_hpp, 0, ',', '.') . '</td>
                    <td>' . 'Rp. ' . number_format($sale->total_price, 0, ',', '.') . '</td>
                    <td>' . 'Rp. ' . number_format($sale->total_vatprice, 0, ',', '.') . '</td>
                </tr>';
            }
            $cardz = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%m') as month"),
                DB::raw("SUM(total_hpp) as total_hpp"),
                DB::raw("SUM(total_price) as total_price"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderBy('createdAt')->get();

            foreach ($cardz as $cardz) {
                $html['day'] .=  '
                    <tr>
                        <th id="total" colspan="4">Total</th>
                        <td>' . 'Rp. ' . number_format($cardz->total_vatprice, 0, ',', '.') . '</td>
                    </tr>';
            }
            return json_encode($html);
            // dd($sale);
        }

        return view('report.index');
    }

    public function resume(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end));
            $html = '';
            $sale_detail = SaleDetail::select(DB::raw('count(menu_name) as count, menu_name'))->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_name')->get();
            foreach ($sale_detail as $sale_detail) {
                $html .= '<tr>
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
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        dd($request->date_start);
        return Excel::download(new SaleReportExport($date_start, $date_end), 'saleReport.xlsx');
    }

    public function month(Request $request)
    {
        // $products = Sale::select(DB::raw('count(created_at) as created_at, created_at'))->groupBy('created_at')->get();
        // $products = Sale::select('id', 'created_at')->get()->groupBy(function ($val) {
        //     return Carbon::parse($val->created_at)->format('F');
        // });
        if ($request->ajax()) {
            $date_start = date("Y-m-d", strtotime($request->date_start));
            $date_end = date("Y-m-d", strtotime($request->date_end));
            $cards = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(total_hpp) as total_hpp"),
                DB::raw("SUM(total_price) as total_price"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderBy('createdAt')->get();


            $html['menuz'] = '';
            $html['tal'] = '';
            $html['cash'] = '';
            $html['bank'] = '';
            $html['menu'] = '';
            $i = 0;
            $j = 0;
            foreach ($cards as $cards) {
                $html['menuz'] .= '<tr>
                    <td>' . ++$i . '</td>
                    <td>' . date("M Y", strtotime($cards->month)) . '</td>
                    <td>' . 'Rp. ' . number_format($cards->total_hpp, 2, ',', '.') . '</td>
                    <td>' . 'Rp. ' . number_format($cards->total_price, 2, ',', '.') . '</td>
                    <td>' . 'Rp. ' . number_format($cards->total_vatprice, 2, ',', '.') . '</td>
                </tr>';
            }

            $card = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_hpp) as total_hpp"),
                DB::raw("SUM(total_price) as total_price"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderBy('createdAt')->get();

            foreach ($card as $card) {
                $html['tal'] .=  '
                    <tr>
                        <th id="total" colspan="4">Total</th>
                        <td>' . 'Rp. ' . number_format($card->total_vatprice, 2, ',', '.') . '</td>
                    </tr>';
            }

            $sale_payment = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'cash')->groupBy('month')->orderBy('createdAt')->get();
            foreach ($sale_payment as $sale) {
                $html['cash'] .= '
                    <h4>Total Cash : <span>' . 'Rp. ' . number_format($sale->total_vatprice, 2, ',', '.') . '</span> </h4>';
            }

            $salez = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'bank transfer')->groupBy('month')->orderBy('createdAt')->get();

            foreach ($salez as $salez) {
                $html['bank'] .= '
                    <h4>Total Transfer Bank : <span>' . 'Rp. ' . number_format($salez->total_vatprice, 2, ',', '.') . '</span> </h4>';
            }

            $saleDetail = SaleDetail::select(DB::raw('count(menu_name) as count, menu_name'))->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_name')->get();
            foreach ($saleDetail as $saleDetail) {
                $html['menu'] .= '
                        <tr>
                            <td>' . ++$j . '</td>
                            <td>' . $saleDetail->menu_name . '</td>
                            <td>' . $saleDetail->count . '</td>
                        </tr>';
            }
            return json_encode($html);
        }
        $a = Sale::select([
            DB::raw("DATE_FORMAT(created_at, '%m-%Y') as month"),
            DB::raw("SUM(total_vatprice) as total_vatprice"),
            DB::raw('max(created_at) as createdAt')
        ])->groupBy('month')->orderBy('createdAt')->get();
        $month_name = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];
        $bulan = [];
        $data = [];
        foreach ($a as $a) {
            $month = $a->month;
            $month_number = explode("-", $month)[0];
            $year = explode("-", $month)[1];
            array_push($bulan, $month_name[$month_number - 1] . ' ' . $year);
            array_push($data, $a->total_vatprice);
        }
        $formatted_array = array_map(function ($num) {
            return number_format($num, 2, ',', '.');
        }, $data);
        // dd($formatted_array);

        return view('report.indexmonth', ['month' => json_encode($bulan), 'data' => json_encode($data)]);
    }

    public function employee(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start . '00:00:00'));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end . '23:59:59'));

            $sales = Sale::select(DB::raw('count(user_name) as count, user_name'))->groupBy('user_name')->whereBetween('updated_at', [$date_start, $date_end])->where('sale_status', 'paid')->where(function ($sale) {
                $sale->whereHas('user', function ($sale) {
                    return $sale->role('cashier');
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
