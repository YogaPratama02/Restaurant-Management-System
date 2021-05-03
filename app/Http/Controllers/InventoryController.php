<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::all();
        return view('inventory.index');
    }

    public function create()
    {
        $inventories = new Inventory();
        return view('inventory.forminventory', compact('inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredients' => 'required|unique:inventories|max:255',
            'stock_quantity' => 'required|numeric',
            'alert_quantity' => 'required|numeric',
            'unit' => 'required|max:255'
        ]);

        $inventories = new Inventory();
        $inventories->ingredients = $request->ingredients;
        $inventories->stock_quantity = $request->stock_quantity;
        $inventories->alert_quantity = $request->alert_quantity;
        $inventories->unit = $request->unit;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $inventories->created_at = $current;
        $inventories->updated_at = $current;
        $inventories->save();
    }

    public function edit($id)
    {
        $inventories = Inventory::findOrFail($id);
        return view('inventory.forminventory', compact('inventories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ingredients' => 'required|max:255',
            'stock_quantity' => 'required|numeric',
            'alert_quantity' => 'required|numeric',
            'unit' => 'required|max:255'
        ]);

        $inventories = Inventory::find($id);
        $inventories->ingredients = $request->ingredients;
        $inventories->stock_quantity = $request->stock_quantity;
        $inventories->alert_quantity = $request->alert_quantity;
        $inventories->unit = $request->unit;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $inventories->updated_at = $current;
        $inventories->save();
    }

    public function destroy($id)
    {
        $inventories = Inventory::findOrFail($id);
        $inventories->delete();
    }

    public function dataTable()
    {
        $inventories = Inventory::all();
        return DataTables()->of($inventories)
            ->addColumn('action', function ($inventories) {
                return view('inventory.inventoryaction', [
                    'inventories' => $inventories,
                    'url_edit' => route('inventory.edit', $inventories->id),
                    'url_destroy' => route('inventory.destroy', $inventories->id)
                ]);
            })
            ->addColumn('stock_quantity', function ($inventories) {
                if ($inventories->stock_quantity <= $inventories->alert_quantity) {
                    $stock_quantity = '';
                    $stock_quantity .= number_format($inventories->stock_quantity, 0, ',', '.');
                    return '<span class="text-danger">' . $stock_quantity . '</span>';
                } else {
                    $stock_quantity = '';
                    $stock_quantity .= number_format($inventories->stock_quantity, 0, ',', '.');
                    return $stock_quantity;
                }
            })

            ->addColumn('alert_quantity', function ($inventories) {
                $alert_quantity = '';
                $alert_quantity .= number_format($inventories->alert_quantity, 0, ',', '.');
                return '<span class="text-danger">' . $alert_quantity . '</span>';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'stock_quantity', 'alert_quantity'])
            ->make(true);
    }

    public function reportInventory(Request $request)
    {
        if ($request->ajax()) {
            $i = 0;
            $inventory = DB::table('inventories')->whereRaw('stock_quantity < alert_quantity')->get();
            $html = '';
            $html .=
                '<div class="card-body">
                <h4 class="text-center">a list of items that must be purchased immediately</h4>
                        <table class="table table-bordered text-center" style="width:100%">
                        <thead>
                            <tr class="text-lite text-center">
                                <th scope="col">No</th>
                                <th scope="col">Ingredients</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($inventory as $inventory) {
                $html .= '<tr>
                        <td>' . ++$i . '</td>
                        <td>' . $inventory->ingredients . '</td>
                    </tr>';
            }
            $html .= '</tbody>
                            </table>
                            </div>
                            ';
            return response()->json($html);
        }
    }
}
