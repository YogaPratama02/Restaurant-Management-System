<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Table;
use Carbon\Carbon;
use DataTables;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('pages.table.index');
    }

    public function create()
    {
        $tables = new Table();
        return view('pages.table.formtable', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:tables|max:255']);
        $table = new Table();
        $table->name = $request->name;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $table->created_at = $current;
        $table->updated_at = $current;
        $table->save();
    }

    public function edit($id)
    {
        $tables = Table::findOrFail($id);
        return view('pages.table.formtable', compact('tables'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:tables|max:255'
        ]);

        $tables = Table::find($id);
        $tables->name = $request->name;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $tables->updated_at = $current;
        // $tables->update($request->all());
        $tables->save();
    }

    public function destroy($id)
    {
        $tables = Table::findOrFail($id);
        $tables->delete();
    }

    public function dataTable()
    {
        $tables = Table::all();
        return DataTables()->of($tables)
            ->addColumn('action', function ($tables) {
                return view('pages.table.tableaction', [
                    'tables' => $tables,
                    'url_edit' => route('table.edit', $tables->id),
                    'url_destroy' => route('table.destroy', $tables->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
