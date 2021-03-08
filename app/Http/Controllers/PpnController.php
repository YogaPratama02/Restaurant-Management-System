<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ppn;
use Carbon\Carbon;

class PpnController extends Controller
{
    public function index()
    {
        $ppn = Ppn::all();
        return view('ppn.index');
    }

    public function create()
    {
        $ppn = new Ppn();
        return view('ppn.formppn', compact('ppn'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ppn' => 'required|numeric'
        ]);

        $ppn = new Ppn();
        $ppn->ppn = $request->ppn;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $ppn->created_at = $current;
        $ppn->updated_at = $current;
        $ppn->save();
    }

    public function edit($id)
    {
        $ppn = Ppn::findOrFail($id);
        return view('ppn.formppn', compact('ppn'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ppn' => 'required|numeric'
        ]);

        $ppn = Ppn::find($id);
        $ppn->ppn = $request->ppn;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $ppn->updated_at = $current;
        $ppn->save();
    }

    public function destroy($id)
    {
        $ppn = Ppn::findOrFail($id);
        $ppn->delete();
    }

    public function dataTable()
    {
        $ppn = Ppn::all();
        return DataTables()->of($ppn)
            ->addColumn('action', function ($ppn) {
                return view('ppn.ppnaction', [
                    'ppn' => $ppn,
                    'url_edit' => route('ppn.edit', $ppn->id),
                    'url_destroy' => route('ppn.destroy', $ppn->id)
                ]);
            })
            ->addColumn('ppn', function ($ppn) {
                // $ppns = '';
                // $ppns .= number_format($ppn->ppn, ',', '.', 0);
                // return "$ppns %";
                return "$ppn->ppn %";
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'ppn'])
            ->make(true);
    }
}
