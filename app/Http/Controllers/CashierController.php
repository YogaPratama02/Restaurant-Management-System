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
use App\Voucher;
use Carbon\Carbon;
use Spatie\Permission\Models\hasRole;
use PDF;

use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class CashierController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('cashier.index', ['categories' => $categories]);
    }

    public function getTables()
    {
        $tables = DB::table('tables')->orderBy('id', 'asc')->get();
        $html = '';
        foreach ($tables as $tables) {
            $html .= '<div class="col-lg-2 col-md-3 col-sm-2 col-3 p-2">';
            $html .= '<button class="btn btn-table text-black" data-id="' . $tables->id . '" data-name="' . $tables->name . '" style="background-color: #97cf6e; width:60px">
            <img class="img-fluid" src="' . url('/images/table.svg') . '" />
            <br>';
            switch ($tables) {
                case ($tables->status == 'unvailable'):
                    $html .= '<span class="badge" style="background-color: #a6a9b6">' . $tables->name . '</span>';
                    break;
                default:
                    $html .= '<span class="badge" style="background-color: #F3B949">' . $tables->name . '</span>';
                    break;
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
                        <button class="btn btn-outline-secondary btn-menu text-left show_menus"  data-id="' . $menu->id . '" disabled>
                            <div class="img_sold">
                                <img class="img_menu_show" src="' . url('/menu_images/' . $menu->image) . '">
                                <div class="text_sold"><p>sold out</p></div>
                            </div>
                            <div class="">
                            <p class="font-weight-bold text_menu"> ' . $menu->name . '</p>
                            <p>Rp' . number_format($menu->price) . '</p>
                            </div>
                        </button>
                    </div>
                    ';
                } else {
                    $html .= '
                    <div class="ml-1 mt-2 show_menus">
                        <button class="btn btn-outline-secondary btn-menu text-left show_menus" data-id="' . $menu->id . '">
                            <img class="rounded img_menu_show" src="' . url('/menu_images/' . $menu->image) . '" >
                            <p class="font-weight-bold text_menu"> ' . $menu->name . '</p>
                            <p>Rp' . number_format($menu->price) . '</p>
                            </div>
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
        $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first();
        // crate penjualan baru
        if (!$sale) {
            $user = auth()->user();
            $sale = new Sale();
            $sale->table_id = $table_id;
            $sale->user_id = $user->id;
            $sale->save();
            $sale_id = $sale->id;
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
                foreach ($inventmenus as $inventmenu) {
                    $invent = Inventory::find($inventmenu->inventory_id);
                    $save = $invent->update([
                        'stock_quantity' => $invent->stock_quantity - ($inventmenu->consumption * ($saledetail->quantity / $saledetail->quantity))
                    ]);
                }
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
        }
        $html = $this->getSaleDetails($sale_id);
        return $html;
    }

    public function getSaleDetailsByTable($table_id)
    {
        $sale = Sale::where('table_id', $table_id)->where('sale_status', 'unpaid')->first();
        $tables = Table::where('status', 'available')->get();
        $html['sale'] = '';
        $html['modal'] = '';
        // cek jika ada saledetail yang mempunya sale
        if ($sale) {
            $sale_id = $sale->id;
            $html['sale'] .= $this->getSaleDetails($sale_id);
            $html['modal'] .= '<button type="button" data-id="' . $sale->table->id . '" class="btn mejaUpdate text-black" style="background-color: #F3B949;" data-toggle="modal" data-target="#modal-move">
                                Move Table
                                </button>';
            $html['modal'] .= '<div class="modal fade" id="modal-move" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <select class="form-control list_table" id="exampleFormControlSelect1">';
            foreach ($tables as $table) {
                $html['modal'] .= '<option value="' . $table->id  . '" class="namaTable">' . $table->name . '</option>';
            }
            $html['modal'] .= '</select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn text-black" data-dismiss="modal" style="background-color: #a6a9b6;">Close</button>
                                <button type="button" class="btn table_update" data-id="' . $sale->id . '" style="background-color: #90be6d;">Update</button>
                            </div>
                            </div>
                        </div>
                        </div>';
        } else {
            $html['sale'] .= '<div class="row justify-content-center align-items-center">
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
        ])->groupBy('ppn')->orderBy('ppn')->get();
        $total = $ppn->sum('ppn');
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->get();
        $sale = Sale::all();
        $voucher = Voucher::where('status', 'Active')->first();

        $html .= '<div class="table-responsive-md" style="overflow-y:scroll; height: 400px; border: 1px; solid #343A40">
        <table class="table table-stripped table-white">
        <thead class="text-white text-left" style="background-color: #97cf6e">
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
            switch ($saleDetail) {
                case ($saleDetail->note == Null):
                    $show_note = '<i class="fas fa-pencil-alt ml-2 mr-2"></i>';
                    break;
                default:
                    $show_note = '<i class="fas fa-comment-dots ml-2 mr-2"></i>';
                    break;
            }

            switch ($saleDetail) {
                case ($saleDetail->status == "NoConfirm"):
                    $html .= '
                    <tr>
                        <td>' . $saleDetail->menu_name . ' <a href="' . route('cashier.note', $saleDetail->id) . '" class="modal_note">' . $show_note . '</a></td>
                        <td>' . $decreaseButton . '  ' . $saleDetail->quantity . ' </td>
                        <td>' . $saleDetail->menu_discount . ' ' . '%' . '</td>
                        <td>' . number_format(($saleDetail->menu_price * $saleDetail->quantity - $saleDetail->menu_price * $saleDetail->quantity * ($saleDetail->menu_discount / 100)), 0, ',', '.') . '</td>';
                    break;
                case ($saleDetail->status == 'confirm' or $saleDetail->status == 'waiting' or $saleDetail->status == 'finish'):
                    $html .= '
                    <tr>
                        <td>' . $saleDetail->menu_name . '</td>
                        <td> ' . $saleDetail->quantity . ' </td>
                        <td>' . $saleDetail->menu_discount . ' ' . '%' . '</td>
                        <td>' . "Rp " . number_format(($saleDetail->menu_price * $saleDetail->quantity - $saleDetail->menu_price * $saleDetail->quantity * ($saleDetail->menu_discount / 100)), 0, ',', '.') . '</td>
                        ';
                    break;
                default:
                    break;
            }

            switch ($saleDetail) {
                case ($saleDetail->status == 'confirm'):
                    $html .= '<td><a data-id="" class="text-black">Confirm..</a></td>';
                    break;
                case ($saleDetail->status == 'waiting'):
                    $html .= '<td><a data-id="" class="text-black">Waiting..</a></td>';
                    break;
                case ($saleDetail->status == 'finish'):
                    $html .= '<td><a data-id="" class="text-black">Ready..</a></td>';
                    break;
                default:
                    break;
            }
            $html .= '</tr>';
        }
        $html .= '<td>PPN: ' . $total . ' %</td>';
        $html .= '</tbody></table>';

        $html .= '<div class="note" style="display: none"></div>';
        $html .= '</div>';
        if ($saleDetail->status == 'NoConfirm' && $saleDetail->sale->customer_name != null) {
            $html .= '<td><button data-id="' . $sale_id . '" class="btn rounded btn-block btn-order-again" style="background-color: #ffd384">Confirm Again..</button></td>';
        }

        $sale = Sale::find($sale_id);
        if ($voucher && $sale->voucher_id == NULL && $saleDetail->status != 'NoConfirm') {
            $html .= '<div class="disc">
                <h5>Masukkan Kode Voucher : ' . $voucher->name . '</h5>
                <form class="form-inline">
                <div class="form-group mb-2">
                <input class="form-control area" placeholder="Voucher..">
                </div>
                <button type="submit" data-id="' . $sale_id . '" class="btn mx-sm-3 mb-2 text-black voucher">Input</button>
                </form></div>';
        }
        $html .= '<hr>';
        switch ($sale) {
            case ($sale->voucher_id == NULL):
                $html .= '<h3 class="try" data-all="' . ($sale->total_price + ($sale->total_price * $total / 100)) . '">Total: Rp ' . number_format($sale->total_price + ($sale->total_price * $total / 100), 0, ',', '.') . '</h3>';
                break;
            default:
                $html .= '<h3 class="try" data-all="' . ($sale->total_price + ($sale->total_price * $total / 100) - ($sale->total_price * $sale->voucher->discount / 100)) . '">Total: Rp ' . number_format($sale->total_price + ($sale->total_price * $total / 100) - ($sale->total_price * $sale->voucher->discount / 100), 0, ',', '.') . '</h3>';
                break;
        }

        if ($saleDetail->status == 'confirm' or $saleDetail->status == 'waiting' or $saleDetail->status == 'finish') {
            $html .= '
            <div class="card-body">
            <div class="panel">
                <div class="row">
                    <td>Payment Method <br>
                    <div class="form-control sizePayment">
                        <span class="radio-item ">
                            <input type="radio" name="payment_type" class="true" value="cash" checked="checked">
                            <label for="payment_type"> <i class="fa fa-money-bill text-success"></i> Cash</label>

                            <input type="radio" name="payment_type" class="true" value="bank_transfer">
                            <label for="payment_type"> <i class="fa fa-university text-danger"></i> Bank Transfer</label>

                            <input type="radio" name="payment_type" class="true" value="payment_card">
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
            if (auth()->user()->hasRole('super admin|admin|cashier')) {
                $html .= '<button data-id="' . $sale_id . '" data-total="' . $sale->total_price . '" class="btn btn-block btn-payment mt-2" style="background-color: #F3B949">Payment</button>';
            } elseif (auth()->User()->hasRole('members')) {
                $html .= '<button class="btn btn-block mt-2" style="background-color: #F3B949">Harap hubungi cashier untuk transaksi</button>';
            }
        } else if ($saleDetail->status == "NoConfirm" && $saleDetail->sale->customer_name == null) {
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
            $html .= '<button data-id="' . $sale_id . '" class="btn btn-block btn-confirm-order" style="background-color: #F3B949">Confirm Order</button>';
        }
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
        $table->update();
        // $tables = Table::where('status', 'available')->get();
        SaleDetail::where('sale_id', $sale_id)->update(['status' => 'confirm']);
        $html = '';
        $html .= $this->getSaleDetails($sale_id);
        return $html;
    }

    public function voucher(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required'
        ]);
        $sale_id = $request->sale_id;
        $sale = Sale::find($sale_id);
        $voucher = Voucher::where('name', $request->voucher_id)->first();
        $sale->voucher_id = $voucher->id;
        $sale_to_voucher = $sale->voucher->discount;
        $sale->save();
        $html['sale'] = '';
        $html['sale_voucher'] = '';
        $html['sale_voucher'] .= $sale_to_voucher;
        $html['sale'] = $this->getSaleDetails($sale_id);
        return $html;
    }

    public function updateTable(Request $request)
    {
        $table_id = $request->table_id;
        $sale = Table::find($table_id);
        $sale->status = 'available';
        $sale->save();
    }

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
        $payment_type = $request->payment_type;
        $ppn = Ppn::select([
            DB::raw("SUM(ppn) as ppn")
        ])->groupBy('ppn')->orderBy('ppn')->first();
        if ($ppn == NULL) {
            $total = 0;
        } else {
            $total = $ppn->sum('ppn');
        }
        $sale = Sale::find($saleID);
        $sale->total_received = $receiveTotal;
        if ($sale->voucher_id != NULL) {
            $sale->change = ($receiveTotal - $sale->total_price) - ($sale->total_price * ($total / 100) - ($sale->total_price * $sale->voucher->discount / 100));
        } else {
            $sale->change = ($receiveTotal - $sale->total_price) - ($sale->total_price * ($total / 100));
        }
        $sale->payment_type = $payment_type;
        $sale->sale_status = 'paid';
        $sale->total_vat = $total;
        if ($sale->voucher_id != NULL) {
            $sale->total_vatprice = ($sale->total_price + ($sale->total_price * $total / 100) - ($sale->total_price * $sale->voucher->discount / 100));
        } else {
            $sale->total_vatprice = ($sale->total_price + ($sale->total_price * $total / 100));
        }
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
        if ($ppn == NULL) {
            $total = 0;
        } else {
            $total = $ppn->sum('ppn');
        }
        return view('cashier.showReceipt', ['sale' => $sale, 'saleDetails' => $saleDetails, 'total' => $total]);
    }

    public function pdf($saleID)
    {
        $sale = Sale::find($saleID);
        $saleDetails = SaleDetail::where('sale_id', $saleID)->get();
        $ppn = Ppn::select([
            DB::raw("SUM(ppn) as ppn")
        ])->groupBy('ppn')->orderBy('ppn')->first();
        if ($ppn == NULL) {
            $total = 0;
        } else {
            $total = $ppn->sum('ppn');
        }
        $pdf = \PDF::loadView('pdf', ['sale' => $sale, 'saleDetails' => $saleDetails, 'total' => $total]);
        return $pdf->download('invoice.pdf');
    }
}
