<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\InventoryMenu;
use App\Menu;
use Carbon\Carbon;
use DataTables;

class InventoryMenuController extends Controller
{
    public function index()
    {
        $inventmenus = InventoryMenu::all();
        return view('inventorymenu.index');
    }

    public function create()
    {
        $model = new InventoryMenu();
        $model['menus'] = Menu::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        $model['inventories'] = Inventory::all()->sortBy('ingredients', SORT_NATURAL | SORT_FLAG_CASE)->pluck('id', 'ingredients');
        return view('inventorymenu.forminvenmenu', ['model' => $model]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|numeric',
            'menu_id' => 'required|numeric',
            'consumption' => 'required|numeric'
        ]);

        $inventmenus = new InventoryMenu();
        $inventmenus->inventory_id = $request->inventory_id;
        $inventmenus->menu_id = $request->menu_id;
        $inventmenus->consumption = $request->consumption;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $inventmenus->created_at = $current;
        $inventmenus->updated_at = $current;
        $inventmenus->save();
    }

    public function edit($id)
    {
        $model = InventoryMenu::find($id);
        $model['menus'] = Menu::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        $model['inventories'] = Inventory::all()->sortBy('ingredients', SORT_NATURAL | SORT_FLAG_CASE)->pluck('ingredients', 'id');
        return view('inventorymenu.forminvenmenu', ['model' => $model]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'inventory_id' => 'required|numeric',
            'menu_id' => 'required|numeric',
            'consumption' => 'required|numeric'
        ]);

        $inventmenus = InventoryMenu::find($id);
        $inventmenus->inventory_id = $request->inventory_id;
        $inventmenus->menu_id = $request->menu_id;
        $inventmenus->consumption = $request->consumption;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $inventmenus->updated_at = $current;
        $inventmenus->save();
    }

    public function destroy($id)
    {
        $inventmenus = InventoryMenu::findOrFail($id);
        $inventmenus->delete();
    }

    public function dataTable()
    {
        $inventmenus = InventoryMenu::all();
        return DataTables()->of($inventmenus)
            ->addColumn('action', function ($inventmenus) {
                return view('inventorymenu.invenmenuaction', [
                    'inventmenus' => $inventmenus,
                    'url_edit' => route('inventmenu.edit', $inventmenus->id),
                    'url_destroy' => route('inventmenu.destroy', $inventmenus->id)
                ]);
            })
            ->addColumn('inventory_id', function ($inventmenus) {
                return $inventmenus->inventory->ingredients;
            })
            ->addColumn('menu_id', function ($inventmenus) {
                return $inventmenus->menu->name;
            })
            ->editColumn('consumption', function ($inventmenus) {
                $consumption = '';
                $consumption .= number_format($inventmenus->consumption, 0, ',', '.');
                return $consumption;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'inventory_id', 'menu_id'])
            ->make(true);
    }
}
