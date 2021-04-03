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
use App\Exports\SaleDayExport;
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
            $html['cash'] = '';
            $html['transfer'] = '';
            $html['credit'] = '';
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
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
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

            $sale_cash = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'cash')->groupBy('month')->orderBy('createdAt')->get();
            foreach ($sale_cash as $sale) {
                $html['cash'] .= 'Rp. ' . number_format($sale->total_vatprice, 0, ',', '.');
            }

            $saleBank = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'bank transfer')->groupBy('month')->orderBy('createdAt')->get();

            foreach ($saleBank as $saleBank) {
                $html['transfer'] .= 'Rp. ' . number_format($saleBank->total_vatprice, 0, ',', '.');
            }

            $saleCard = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'payment Card')->groupBy('month')->orderBy('createdAt')->get();

            foreach ($saleCard as $saleCard) {
                $html['credit'] .= 'Rp. ' . number_format($saleCard->total_vatprice, 0, ',', '.');
            }

            return json_encode($html);
        }

        return view('report.index');
    }

    public function resume(Request $request)
    {
        if ($request->ajax()) {
            $date_start = date("Y-m-d H:i:s", strtotime($request->date_start));
            $date_end = date("Y-m-d H:i:s", strtotime($request->date_end));
            $html = '';
            $i = 0;
            // $sale_detail = SaleDetail::select(DB::raw('count(menu_id) as count, menu_id'))->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_id')->get();
            $sale_detail = DB::table('sale_details')->selectRaw('menu_name, SUM(quantity) as count')->groupBy('menu_name')->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_name')->get();
            foreach ($sale_detail as $sale_detail) {
                $html .= '<tr>
                    <td>' . ++$i . '</td>
                    <td>' . $sale_detail->menu_name . '</td>
                    <td>' . $sale_detail->count  . '</td>
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
        // dd($date_end);
        // dd($request->date_start);
        return Excel::download(new SaleReportExport($date_start, $date_end), 'saleReport.xlsx');
    }

    public function dayExcel(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        // dd($date_start);
        return Excel::download(new SaleDayExport($date_start, $date_end), 'saleReport.xlsx');
    }

    public function month(Request $request)
    {
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
            $html['card'] = '';
            $html['menu'] = '';
            $i = 0;
            $j = 0;
            foreach ($cards as $cards) {
                $html['menuz'] .= '<tr>
                    <td>' . ++$i . '</td>
                    <td>' . date("M Y", strtotime($cards->month)) . '</td>
                    <td>' . 'Rp. ' . number_format($cards->total_hpp, 0, ',', '.') . '</td>
                    <td>' . 'Rp. ' . number_format($cards->total_price, 0, ',', '.') . '</td>
                    <td>' . 'Rp. ' . number_format($cards->total_vatprice, 0, ',', '.') . '</td>
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
                        <td>' . 'Rp. ' . number_format($card->total_vatprice, 0, ',', '.') . '</td>
                    </tr>';
            }

            $sale_payment = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'cash')->groupBy('month')->orderBy('createdAt')->get();
            foreach ($sale_payment as $sale) {
                $html['cash'] .= 'Rp. ' . number_format($sale->total_vatprice, 0, ',', '.');
            }

            $salez = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'bank transfer')->groupBy('month')->orderBy('createdAt')->get();

            foreach ($salez as $salez) {
                $html['bank'] .= 'Rp. ' . number_format($salez->total_vatprice, 0, ',', '.');
            }

            $saleCard = Sale::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total_vatprice) as total_vatprice"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->where('payment_type', 'payment Card')->groupBy('month')->orderBy('createdAt')->get();

            foreach ($saleCard as $saleCard) {
                $html['card'] .= 'Rp. ' . number_format($saleCard->total_vatprice, 0, ',', '.');
            }

            // $saleDetail = SaleDetail::select(DB::raw('count(menu_name) as count, menu_name'))->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_name')->get();
            $saleDetail = DB::table('sale_details')->selectRaw('menu_name, SUM(quantity) as count')->groupBy('menu_name')->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('menu_name')->get();
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
            $date_start = date("Y-m-d", strtotime($request->date_start));
            $date_end = date("Y-m-d", strtotime($request->date_end));
            $html['total'] = '';
            $html['footer'] = '';
            $html['detail'] = '';
            $i = 0;
            $j = 0;
            $purchase = Supplier::select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(total) as total"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderBy('createdAt')->get();
            // $purchase = Supplier::whereBetween('updated_at', [$date_start, $date_end])->get();
            foreach ($purchase as $purchase) {
                $html['total'] .= '<tr>
                    <td>' . ++$i . '</td>
                    <td>' . $purchase->month . '</td>
                    <td>' . 'Rp. ' .  number_format($purchase->total, 0, ',', '.') . '</td>
                </tr>';
            }

            $purc = Supplier::select([
                DB::raw("DATE_FORMAT(created_at, '%Y') as month"),
                DB::raw("SUM(total) as total"),
                DB::raw('max(created_at) as createdAt')
            ])->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('month')->orderBy('createdAt')->get();

            foreach ($purc as $purc) {
                $html['footer'] = '<tr>
                        <th id="total" colspan="2">Total</th>
                        <td>' . 'Rp. ' . number_format($purc->total, 0, ',', '.') . '</td>
                    </tr>';
            }

            $detail = Supplier::select(DB::raw('count(name) as count, name'))->whereBetween(DB::raw('DATE(created_at)'), [$date_start, $date_end])->groupBy('name')->get();

            foreach ($detail as $detail) {
                $html['detail'] .= '<tr>
                    <td>' . ++$j . '</td>
                    <td>' . $detail->name . '</td>
                    <td>' . $detail->count . '</td>
                </tr>';
            }
            return json_encode($html);
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
