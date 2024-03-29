<?php

namespace App\Http\Controllers;

use App\Exports\SupplierExport;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier.index');
    }

    public function create()
    {
        $suppliers = new Supplier();
        return view('supplier.formsupplier', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|max:255',
            'total' => 'required|numeric',
        ]);
        $user = auth()->user();
        $suppliers = new Supplier();
        $suppliers->date = $request->date;
        $suppliers->name = $request->name;
        $suppliers->total = $request->total;
        $suppliers->user_id = $user->id;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $suppliers->created_at = $current;
        $suppliers->updated_at = $current;
        $suppliers->save();
    }

    public function edit($id)
    {
        $suppliers = Supplier::findOrFail($id);
        return view('supplier.formsupplier', compact('suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|max:255',
            'total' => 'required|numeric',
        ]);

        $suppliers = Supplier::find($id);
        $suppliers->date = $request->date;
        $suppliers->name = $request->name;
        $suppliers->total = $request->total;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $suppliers->updated_at = $current;
        $suppliers->save();
    }

    public function destroy($id)
    {
        $suppliers = Supplier::findOrFail($id);
        $suppliers->delete();
    }

    public function export()
    {
        return Excel::download(new SupplierExport(), 'SupplierReport.xlsx');
    }

    public function dataTable()
    {
        $suppliers = Supplier::orderBy('date', 'DESC');
        return DataTables()->of($suppliers)
            ->addColumn('action', function ($suppliers) {
                return view('supplier.supplieraction', [
                    'suppliers' => $suppliers,
                    'url_edit' => route('supplier.edit', $suppliers->id),
                    'url_destroy' => route('supplier.destroy', $suppliers->id),
                ]);
            })
            ->editColumn('date', function ($suppliers) {
                $date = date('d M Y', strtotime($suppliers->date));
                return $date;
            })
            ->editColumn('total', function ($suppliers) {
                $total = 'Rp. ';
                $total .= number_format($suppliers->total, 0, ',', '.');
                return $total;
            })
            ->addColumn('user_id', function ($suppliers) {
                return $suppliers->user->name;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'total', 'date', 'user_id'])
            ->make(true);
    }
}
