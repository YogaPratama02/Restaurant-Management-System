<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomBooking;
use App\Table;
use Carbon\Carbon;
use DataTables;

class RoomBookingController extends Controller
{
    public function index()
    {
        $roombook = RoomBooking::all();
        return view('roombooking.index');
    }

    public function create()
    {
        $model = new RoomBooking();
        $model['tables'] = Table::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        return view('roombooking.formroombook', ['model' => $model]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|numeric',
            'date' => 'required|date',
            'start' => 'required|max:255',
            'end' => 'required|max:255',
            'price' => 'required|numeric'
        ]);
        // dd($request);

        $roombook = new RoomBooking();
        $roombook->table_id = $request->table_id;
        $roombook->date = $request->date;
        $roombook->start = $request->start;
        $roombook->end = $request->end;
        $roombook->price = $request->price;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $roombook->created_at = $current;
        $roombook->updated_at = $current;
        $roombook->save();
    }

    public function edit($id)
    {
        $model = RoomBooking::findOrFail($id);
        $model['tables'] = Table::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        return view('roombooking.formroombook', ['model' => $model]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'table_id' => 'required|numeric',
            'date' => 'required|date',
            'start' => 'required|max:255',
            'end' => 'required|max:255',
            'price' => 'required|numeric'
        ]);

        $roombook = RoomBooking::find($id);
        $roombook->table_id = $request->table_id;
        $roombook->date = $request->date;
        $roombook->start = $request->start;
        $roombook->end = $request->end;
        $roombook->price = $request->price;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $roombook->updated_at = $current;
        $roombook->save();
    }

    public function destroy($id)
    {
        $roombook = RoomBooking::findOrFail($id);
        $roombook->delete();
    }

    public function dataTable()
    {
        $roombook = Roombooking::all();
        return DataTables()->of($roombook)
            ->addColumn('action', function ($roombook) {
                return view('roombooking.roombookaction', [
                    'roombook' => $roombook,
                    'url_edit' => route('roombooking.edit', $roombook->id),
                    'url_destroy' => route('roombooking.destroy', $roombook->id)
                ]);
            })
            ->addColumn('table_id', function ($roombook) {
                return $roombook->table->name;
            })
            ->addColumn('price', function ($roombook) {
                $price = 'Rp. ';
                $price .= number_format($roombook->price, 0, ',', '.');
                return $price;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'table_id', 'price'])
            ->make(true);
    }
}
