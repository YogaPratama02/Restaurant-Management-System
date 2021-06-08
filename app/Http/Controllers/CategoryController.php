<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.management.category');
    }

    public function create()
    {
        $categories = new Category();
        return view('pages.management.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required']
        ]);
        $model = Category::create([
            'name' => $request->name
        ]);
        return response()->json($model);
    }

    public function edit($id)
    {
        $categories = Category::findOrFail($id);
        return view('pages.management.form', compact('categories'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required']
        ]);
        $model = Category::findOrFail($id);

        $model->update([
            'name' => $request->name,
        ]);

        return response()->json($model);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(true);
    }

    public function data()
    {
        $categories = Category::get();
        return DataTables()->of($categories)
            ->addColumn('action', function ($categories) {
                return view('layouts.action', [
                    'categories' => $categories,
                    'url_edit' => route('category.edit', $categories->id),
                    'url_destroy' => route('category.destroy', $categories->id)
                ]);
            })
            ->addIndexColumn()
            ->removeColumn([])
            ->rawColumns([])
            ->make(true);
    }
}
