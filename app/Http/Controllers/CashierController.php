<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Table;
use App\Category;
use App\Menu;
use App\Sale;
use App\SaleDetail;
use App\InventoryMenu;
use Illuminate\Support\Facades\DB;
use App\Inventory;
use App\Ppn;
use Carbon\Carbon;
use PDF;

use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('cashier.index')->with('categories', $categories);
    }

    public function getTables()
    {
        $tables = Table::all();
        $html = '';
        foreach ($tables as $table) {
            $html .= '<div class="col-md-1 mb-3 mt-2">';
            $html .=
                '<button class="btn btn-table text-white" data-id="' . $table->id . '" data-name="' . $table->name . '" style="background-color: #ffd56b">
            <img class="img-fluid" src="' . url('/images/table.svg') . '" />
            <br>';
            if ($table->status == 'available') {
                $html .= '<span class="badge" style="background-color: #ff7b54">' . $table->name . '</span>';
            } else {
                $html .= '<span class="badge" style="background-color: #a6a9b6">' . $table->name . '</span>';
            }
            $html .= '</button>';
            $html .= '</div>';
        }

        return $html;
    }

    public function getMenu($category_id)
    {
        $menus = Menu::where('category_id', $category_id)->get();
        $html = '';
        foreach ($menus as $menu) {
            $inventmenus = InventoryMenu::where('menu_id', $menu->id)->get();
            foreach ($inventmenus as $inventmenu) {
                $invents = Inventory::find($inventmenu->inventory_id);

                if ($invents->stock_quantity < $invents->alert_quantity) {
                    $html .= '
                    <div class="ml-1 mt-2">
                        <button class="btn btn-outline-secondary btn-menu" data-id="' . $menu->id . '" disabled>
                            <img class="img-fluid" style="height:130px; width:130px;" src="' . url('/menu_images/' . $menu->image) . '">
                            <br>
                            Sold Out
                            <br>
                            ' . $menu->name . '
                            <br>
                            Rp' . number_format($menu->price) . '
                        </button>
                    </div>
                    ';
                } else {
                    $html .= '
                    <div class="ml-1 mt-2">
                        <button class="btn btn-outline-secondary btn-menu" data-id="' . $menu->id . '">
                            <img class="rounded" style="height:120px; width:110px;" src="' . url('/menu_images/' . $menu->image) . '">
                            <p class="font-weight-bold sm" > ' . $menu->name . '</p>
                            Rp' . number_format($menu->price) . '
                        </button>
                    </div>
                    ';
                }
            }
        }
        return $html;
    }

    public function getOrder(Request $request)
    {
        $menu = Menu::find($request->menu_id);
        $table_id = $request->table_id;
        // $table_name = $request->table_name;
        $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first();
        // crate penjualan baru
        if (!$sale) {
            $user = Auth::user();
            $sale = new Sale();
            $sale->table_id = $table_id;
            // $sale->table_name = $table_name;
            $sale->user_id = $user->id;
            $sale->user_name = $user->name;
            $sale->save();
            $sale_id = $sale->id;
            // update status table
            // $table = Table::find($table_id);
            // $table->status = "available";
            // $table->save();
        } else {
            // jika sudah ada order
            $sale_id = $sale->id;
            $saledetail = SaleDetail::where('sale_id', $sale_id)->where('menu_id', $menu->id)->where('status', 'NoConfirm')->first();

            if ($saledetail) {
                $saledetail->quantity++;
                $saledetail->save();
                $sale->total_price = $sale->total_price + ($request->quantity * $menu->price) - ($request->quantity * $menu->price * ($menu->discount / 100));
                $sale->total_hpp = $sale->total_hpp + ($request->quantity * $menu->hpp);
                $sale->save();
                $inventmenus = InventoryMenu::where('menu_id', $menu->id)->get();
                // dd($inventmenus);
                foreach ($inventmenus as $inventmenu) {
                    $invent = Inventory::find($inventmenu->inventory_id);
                    $save = $invent->update([
                        'stock_quantity' => $invent->stock_quantity - ($inventmenu->consumption * ($saledetail->quantity / $saledetail->quantity))
                    ]);
                    // dd($save);
                }
                // dd($request);
                $html = $this->getSaleDetails($sale_id);
                return $html;
            }
        }
        // tambah menu pesanan ke sale_detail
        $saleDetail = new SaleDetail();
        $saleDetail->sale_id = $sale_id;
        $saleDetail->menu_id = $menu->id;
        $saleDetail->menu_name = $menu->name;
        $saleDetail->menu_price = $menu->price;
        $saleDetail->menu_discount = $menu->discount;
        $saleDetail->quantity = $request->quantity;
        // $saleDetail_id = $saleDetail->id;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $saleDetail->created_at = $current;
        $saleDetail->updated_at = $current;
        $saleDetail->save();
        $sale->total_hpp = $sale->total_hpp + ($request->quantity * $menu->hpp);
        $sale->total_price = ($sale->total_price + ($request->quantity * $menu->price) - ($request->quantity * $menu->price * ($menu->discount / 100)));

        $sale->save();

        $inventmenus = InventoryMenu::where('menu_id', $menu->id)->get();
        // dd($inventmenus);
        foreach ($inventmenus as $inventmenu) {
            $invent = Inventory::find($inventmenu->inventory_id);
            $save = $invent->update([
                'stock_quantity' => $invent->stock_quantity - ($inventmenu->consumption * $saleDetail->quantity)
            ]);
            // dd($save);
        }
        $html = $this->getSaleDetails($sale_id);
        return $html;
    }

    public function getSaleDetailsByTable($table_id)
    {
        $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first();
        $tables = Table::where('status', 'available')->get();
        $html['a'] = '';
        $html['b'] = '';
        // cek jika ada saledetail yang mempunya sale
        if ($sale) {
            $sale_id = $sale->id;
            $html['a'] .= $this->getSaleDetails($sale_id);
            $html['b'] .= '<button type="button" data-id="' . $sale->table->id . '" class="btn mejaUpdate text-white" style="background-color: #ff7b54;" data-toggle="modal" data-target="#exampleModal">
                                Move Table
                            </button>';
            $html['b'] .= '<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body text-left">
                                <div class="form-group">
                                    <label for="" class="control-label">Select Table</label>
                                    <select class="form-control riri" id="exampleFormControlSelect1">';
            foreach ($tables as $table) {
                $html['b'] .= '<option value="' . $table->id  . '" class="namaTable">' . $table->name . '</option>';
            }
            $html['b'] .= '</select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary table_update" data-id="' . $sale->id . '">Update</button>
                            </div>
                            </div>
                        </div>
                        </div><hr>';
        } else {
            $html['a'] .= '<div class="row justify-content-center align-items-center">
                            <h4>No Order</h4>
                        </div>';
        }
        return $html;
    }

    private function getSaleDetails($sale_id)
    {
        $html = '<p hidden>Sale ID: ' . $sale_id . '</p>';
        $ppn = Ppn::select([
            DB::raw("SUM(ppn) as ppn")
        ])->groupBy('ppn')->orderBy('ppn')->first();
        $total = $ppn->sum('ppn');
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->get();
        $sale = Sale::all();

        $html .= '<div class="table-responsive-md" style="overflow-y:scroll; height: 400px; border: 1px; solid #343A40">
        <table class="table table-stripped table-white">
        <thead class="text-white text-left" style="background-color: #ff7b54">
            <tr>
                <th scope="col">Menu</th>
                <th scope="col">Qty</th>
                <th scope="col">Disc</th>
                <th scope="col">Total</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody class="text-left">';
        foreach ($saleDetails as $saleDetail) {
            $decreaseButton = '<button class="btn btn-danger btn-sm btn-decrease-quantity" style="background-color: #a6a9b6 disabled>-</button>';

            if ($saleDetail->quantity > 0) {
                $decreaseButton = '<button data-id="' . $saleDetail->id . '" class="btn btn-sm btn-decrease-quantity" style="background-color: #a6a9b6">-</button>';
            }
            if ($saleDetail->note == Null) {
                $b = '<i class="fas fa-pencil-alt ml-2 mr-2"></i>';
            }
            if ($saleDetail->note != Null) {
                $b = '<i class="fas fa-comment-dots ml-2 mr-2"></i>';
            }

            if ($saleDetail->status == "NoConfirm") {
                $html .= '
                <tr>
                    <td>' . $saleDetail->menu_name . ' <a href="' . route('cashier.note', $saleDetail->id) . '" class="cape">' . $b . '</a></td>
                    <td>' . $decreaseButton . '  ' . $saleDetail->quantity . ' </td>
                    <td>' . $saleDetail->menu_discount . ' ' . '%' . '</td>
                    <td>' . number_format(($saleDetail->menu_price * $saleDetail->quantity - $saleDetail->menu_price * $saleDetail->quantity * ($saleDetail->menu_discount / 100)), 0, ',', '.') . '</td>';
            }

            if ($saleDetail->status == 'confirm' or $saleDetail->status == 'waiting' or $saleDetail->status == 'finish') {
                // $html .= '<td><a data-id="' . $saleDetail->id . '" class="btn btn-danger btn-delete-saledetail"><i class="fas fa-trash"></i></a></td>';
                $html .= '
                <tr>
                <td>' . $saleDetail->menu_name . '</td>
                <td> ' . $saleDetail->quantity . ' </td>
                <td>' . $saleDetail->menu_discount . ' ' . '%' . '</td>
                <td>' . "Rp " . number_format(($saleDetail->menu_price * $saleDetail->quantity - $saleDetail->menu_price * $saleDetail->quantity * ($saleDetail->menu_discount / 100)), 0, ',', '.') . '</td>
                ';
            }
            if ($saleDetail->status == 'confirm') {
                $html .=
                    '<td><a data-id="" class="btn text-white" style="background-color: #ffa45b; border-radius: 25px;">Confirm..</a></td>';
            }
            if ($saleDetail->status == 'waiting') {
                $html .= '<td><a data-id="" class="btn text-white" style="background-color: #ffa45b; border-radius: 25px;">Waiting..</a></td>';
            }
            if ($saleDetail->status == 'finish') {
                $html .= '<td><a data-id="" class="btn text-white" style="background-color: #ffa45b; border-radius: 25px;">Ready..</a></td>';
            }

            $html .= '</tr>';
        }
        // foreach ($ppn as $ppn) {
        $html .= '<td>PPN: ' . $total . ' %</td>';
        // }
        $html .= '</tbody></table>';

        $html .= '<div class="note" style="display: none"></div>';
        $html .= '</div>';
        if ($saleDetail->status == 'NoConfirm' && $saleDetail->sale->customer_name != null) {
            $html .= '<td><button data-id="' . $sale_id . '" class="btn rounded btn-block text-white btn-order-again" style="background-color: #ffba08">Confirm..</button></td>';
        }

        $sale = Sale::find($sale_id);
        $html .= '<hr>';
        $html .= '<h3 class="try" data-all="' . ($sale->total_price + ($sale->total_price * $total / 100)) . '">Total: Rp ' . number_format($sale->total_price + ($sale->total_price * $total / 100), 0, ',', '.') . '</h3>';

        $detail = SaleDetail::where('sale_id', $sale_id)->first();
        if ($detail->status == 'confirm' or $detail->status == 'waiting' or $saleDetail->status == 'finish') {
            $html .= '
            <div class="card-bod">
            <div class="panel">
                <div class="row">
                    <td>Payment Method <br>
                    <div class="form-control sizePayment">
                        <span class="radio-item ">
                            <input type="radio" name="payment_type" class="true" value="cash" checked="checked">
                            <label for="payment_type"> <i class="fa fa-money-bill text-success"></i> Cash</label>

                            <input type="radio" name="payment_type" class="true" value="bank transfer">
                            <label for="payment_type"> <i class="fa fa-university text-danger"></i> Bank Transfer</label>

                            <input type="radio" name="payment_type" class="true" value="payment Card">
                            <label for="payment_type"> <i class="fa fa-credit-card text-info"></i> Payment Card</label>
                        </span>
                    </div>
                    </td><br>
                    <td>
                        Payment
                        <input type="number" name="paid_amout" id="paid_amount" class="form-control">
                    </td>
                    <td>
                        Returning Change
                        <input type="number" readonly name="balance" id="balance" class="form-control">
                    </td>
                </div>
            </div>
        </div>
            ';
            if ($saleDetail->status == "NoConfirm") {
                $html .= '<button data-id="' . $sale_id . '" data-total="' . $sale->total_price . '" class="btn btn-block btn-payment mt-2" style="background-color: #ff7b54" disabled>Payment</button>';
            } else {
                $html .= '<button data-id="' . $sale_id . '" data-total="' . $sale->total_price . '" class="btn btn-block btn-payment mt-2" style="background-color: #ff7b54">Payment</button>';
            }
        } else {
            $html .= '
            <table class="table table-striped">
                        <tr>
                            <td>
                                <label for="">Customer Name</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control">
                            </td>
                            <td>
                                <label for="">Customer Phone</label>
                                <input type="number" name="customer_phone" id="customer_phone" class="form-control">
                            </td>
                        </tr>
                    </table>
                ';
            $html .= '<button data-id="' . $sale_id . '" class="btn btn-block btn-confirm-order" style="background-color: #ffab73">Confirm Order</button>';
        }
        // $saleDetails = SaleDetail::where('sale_id', $sale_id)->where(['status' => 'confirm']);
        return $html;
    }

    public function confirmAgain(Request $request)
    {
        $sale_id = $request->sale_id;
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->where('status', 'NoConfirm')->update(['status' => 'confirm']);
        $html = '';
        $html = $this->getSaleDetails($sale_id);
        return $html;
    }

    public function confirmOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:255'
        ]);
        $string = "";
        if ($request->customer_phone) {
            $request->validate([
                'customer_phone' => 'required|max:15'
            ]);
            $string = $request->customer_phone;
        }
        $total_price = $request->total_price;
        $sale_id = $request->sale_id;
        $sale = Sale::find($sale_id);
        $sale->customer_name = $request->customer_name;
        $sale->customer_phone = $string;

        $sale->save();
        $table = Table::find($sale->table_id);
        $table->status = 'unvailable';
        $table->save();
        $tables = Table::where('status', 'available')->get();
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->update(['status' => 'confirm']);
        // $saleDetails = SaleDetail::where('sale_id', $sale_id)->where('status', 'confirm')->first();
        $html = '';
        $html .= $this->getSaleDetails($sale_id);
        return $html;
    }

    public function updateTable(Request $request)
    {
        // $request->validate([
        //     'table_id' => 'required|numeric'
        // ]);
        $table_id = $request->table_id;
        $sale = Table::find($table_id);
        $sale->status = 'available';
        $sale->save();
    }

    // public function increaseQuantity(Request $request)
    // {
    //     // dd($request);
    //     // update
    //     $saleDetail_id = $request->saleDetail_id;
    //     $saleDetail = SaleDetail::where('id', $saleDetail_id)->first();
    //     $saleDetail->quantity = $saleDetail->quantity + 1;
    //     $saleDetail->save();

    //     // update
    //     $sale = Sale::where('id', $saleDetail->sale_id)->first();
    //     $sale->total_price = $sale->total_price + $saleDetail->menu_price - ($saleDetail->menu_price * ($saleDetail->menu_discount/100));
    //     $sale->save();

    //     $html = $this->getSaleDetails($saleDetail->sale_id);

    //     return $html;
    // }

    public function decreaseQuantity(Request $request)
    {
        // update
        $saleDetail_id = $request->saleDetail_id;
        $saleDetail = SaleDetail::where('id', $saleDetail_id)->first();
        $sale_id = $saleDetail->sale_id;
        $saleDetail->quantity = $saleDetail->quantity - 1;
        $saleDetail->save();

        // update
        $sale = Sale::where('id', $saleDetail->sale_id)->first();
        $menus = Menu::find($saleDetail->menu_id);
        $sale->total_hpp = $sale->total_hpp -  $menus->hpp;
        $sale->total_price = $sale->total_price - $saleDetail->menu_price + ($saleDetail->menu_price * ($saleDetail->menu_discount / 100));
        $sale->save();

        $menu = Menu::find($saleDetail->menu_id);
        $inventmenus = InventoryMenu::where('menu_id', $menu->id)->get();
        foreach ($inventmenus as $inventmenu) {
            $invent = Inventory::find($inventmenu->inventory_id);
            $save = $invent->update([
                'stock_quantity' => $invent->stock_quantity + ($inventmenu->consumption)
            ]);
        }

        if ($saleDetail->quantity == 0) {
            $saleDetail->delete();
        }
        $salez = SaleDetail::where('sale_id', $sale_id)->first();
        if ($salez == null) {
            $sisi = Sale::find($sale_id)->delete();
            $html = '<div class="row justify-content-center align-items-center">
                        <h4>No Order</h4>
                    </div>';
        } else {
            $html = $this->getSaleDetails($saleDetail->sale_id);
        }
        return $html;
    }

    public function notes($id)
    {
        $saleDetail = SaleDetail::find($id);
        return view('cashier.note')->with('saleDetail', $saleDetail);
    }

    public function mejaPindah(Request $request)
    {
        $table_id = $request->table_id;
        $sale_id = $request->sale_id;
        $sales = Sale::find($sale_id);
        $sales->table_id = $table_id;
        $sales->save();
        $table = Table::find($sales->table_id);
        $table->status = 'unvailable';
        $table->save();
    }

    public function requestNotes(Request $request)
    {
        $request->validate([
            'note' => 'max:255'
        ]);
        $string = "";
        if ($request->note) {
            $request->validate([
                'note' => 'max:255'
            ]);
            $string = $request->note;
        }
        $saleDetail_id = $request->saleDetail_id;
        $saleDetail = SaleDetail::find($saleDetail_id);
        $saleDetail->note = $string;
        $saleDetail->save();
        $sale_id = $saleDetail->sale_id;
        $html = '';
        $html = $this->getSaleDetails($sale_id);
        return $html;
    }

    public function savePayment(Request $request)
    {
        $saleID = $request->saleID;
        $receiveTotal = $request->receiveTotal;
        $paymentType = $request->paymentType;
        $ppn = Ppn::select([
            DB::raw("SUM(ppn) as ppn")
        ])->groupBy('ppn')->orderBy('ppn')->first();
        $total = $ppn->sum('ppn');
        $sale = Sale::find($saleID);
        $sale->total_received = $receiveTotal;
        $sale->change = ($receiveTotal - $sale->total_price) - ($sale->total_price * ($total / 100));
        $sale->payment_type = $paymentType;
        $sale->sale_status = 'paid';
        $sale->total_vat = $total;
        $sale->total_vatprice = ($sale->total_price + ($sale->total_price * $total / 100));
        $current = new Carbon;
        $current->timezone('GMT+7');
        $sale->created_at = $current;
        $sale->updated_at = $current;
        $sale->save();

        // update table to available
        $table = Table::find($sale->table_id);
        $table->status = 'available';
        $table->save();

        // update sale detail
        $current = new Carbon;
        $current->timezone('GMT+7');
        $saleDetails = SaleDetail::where('sale_id', $saleID)->update(['status' => 'done', 'updated_at' => $current]);

        return '/cashier/showReceipt/' . $saleID;
    }

    public function showReceipt($saleID)
    {
        $sale = Sale::find($saleID);
        $saleDetails = SaleDetail::where('sale_id', $saleID)->get();
        $ppn = Ppn::select([
            DB::raw("SUM(ppn) as ppn")
        ])->groupBy('ppn')->orderBy('ppn')->first();
        $total = $ppn->sum('ppn');
        return view('cashier.showReceipt', ['sale' => $sale, 'saleDetails' => $saleDetails, 'total' => $total]);
    }

    // public function pdfDonwload($saleID)
    // {
    //     $sale = Sale::find($saleID);
    //     $saleDetails = SaleDetail::where('sale_id', $saleID)->get();
    //     $ppn = Ppn::select([
    //         DB::raw("SUM(ppn) as ppn")
    //     ])->groupBy('ppn')->orderBy('ppn')->first();
    //     $total = $ppn->sum('ppn');
    //     return view('pdf', ['sale' => $sale, 'saleDetails' => $saleDetails, 'total' => $total]);
    // }

    public function pdf($saleID)
    {
        $sale = Sale::find($saleID);
        $saleDetails = SaleDetail::where('sale_id', $saleID)->get();
        $ppn = Ppn::select([
            DB::raw("SUM(ppn) as ppn")
        ])->groupBy('ppn')->orderBy('ppn')->first();
        $total = $ppn->sum('ppn');
        // $data['judul'] = 'The Propesors Caffe';
        $pdf = \PDF::loadView('pdf', ['sale' => $sale, 'saleDetails' => $saleDetails, 'total' => $total]);
        return $pdf->download('invoice.pdf');
    }
}
