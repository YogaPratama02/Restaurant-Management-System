<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Menu;
use DataTables;
use Carbon\Carbon;

class MenuController extends Controller
{
    public function index()
    {
        return view('pages.menu.index');
    }

    public function create()
    {
        $model = new Menu();
        // $model['categories'] = Category::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        $categories = Category::get();
        return view('pages.menu.formmenu', ['model' => $model, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus|max:255',
            'hpp' => 'required|numeric',
            'price' => 'required|numeric',
            'discount' => 'required|numeric',
            'category_id' => 'required|numeric',
            'description' => 'required',
        ]);
        if ($request->file('image') != null) {
            $directory = '/uploads/menus/';
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $image = $directory . $filename;
            $request->image->move(public_path($directory), $filename);
        } else {
            $image = '/images/no-image.png';
        }

        $menus = new Menu();
        $menus->name = $request->name;
        $menus->description = $request->description;
        $menus->hpp = $request->hpp;
        $menus->price = $request->price;
        $menus->image = $image;
        $menus->discount = $request->discount;
        $menus->category_id = $request->category_id;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $menus->created_at = $current;
        $menus->updated_at = $current;
        $menus->save();
        return json_encode($menus);
    }


    public function edit($id)
    {
        $model = Menu::find($id);
        // $model['categories'] = Category::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        $categories = Category::get();
        return view('pages.menu.formmenu', ['model' => $model, 'categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'hpp' => 'required|numeric',
            'price' => 'required|numeric',
            'discount' => 'required|numeric',
            'category_id' => 'required|numeric'
        ]);

        $menus = Menu::find($id);

        if ($request->file('image') != null) {
            $directory = '/uploads/menus/';
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $image = $directory . $filename;
            $request->image->move(public_path($directory), $filename);
        } else {
            $image = $menus->image;
        }

        // save information to menus table
        $menus->name = $request->name;
        $menus->description = $request->description;
        $menus->hpp = $request->hpp;
        $menus->price = $request->price;
        $menus->image = $image;
        $menus->discount = $request->discount;
        $menus->category_id = $request->category_id;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $menus->updated_at = $current;
        $menus->save();
        return json_encode($menus);
    }

    public function destroy($id)
    {
        $model = Menu::findOrFail($id);
        $model->delete();
    }

    public function show($id)
    {
        $model = Menu::findOrFail($id);
        return view('pages.menu.formdetail', ['model' => $model]);
    }

    public function dataTable()
    {
        $menus = Menu::all();
        return DataTables()->of($menus)
            ->addColumn('action', function ($menus) {
                return view('pages.menu.menuaction', [
                    'menus' => $menus,
                    'url_edit' => route('menu.edit', $menus->id),
                    'url_destroy' => route('menu.destroy', $menus->id),
                    'url_show' => route('menu.show', $menus->id),
                ]);
            })
            ->editColumn('price', function ($menus) {
                $price = 'Rp. ';
                $price .= number_format($menus->price, 0, ',', '.');
                return $price;
            })
            ->editColumn('hpp', function ($menus) {
                $hpp = 'Rp. ';
                $hpp .= number_format($menus->hpp, 0, ',', '.');
                return $hpp;
            })
            ->addColumn('category_id', function ($menus) {
                return $menus->category->name;
            })
            ->addColumn('discount', function ($menus) {
                return "$menus->discount %";
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'hpp', 'price', 'image', 'category_id', 'discount'])
            ->make(true);
    }
}
