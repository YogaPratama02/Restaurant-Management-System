<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Carbon\Carbon;
use App\Menu;
use DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        // if ($request->ajax()) {
        //     return datatables()->of($categories)
        //         ->addIndexColumn()
        //         ->editColumn('edit', function ($categories) {
        //             return $categories;
        //         })
        //         ->make(true);
        // }
        return view('pages.management.category');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = new Category();
        return view('pages.management.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255'
        ]);
        //     // $category = $request->all();


        $category = new Category;
        $category->name = $request->name;
        $current = new Carbon;
        $current->timezone('GMT+7');
        $category->created_at = $current;
        $category->updated_at = $current;
        $category->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::findOrFail($id);
        return view('pages.management.form', compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255'
        ]);

        $categories = Category::findOrFail($id);
        $current = new Carbon;
        $current->timezone('GMT+7');
        $categories->updated_at = $current;
        $categories->update($request->all());
        // $category = Category::where('name', $request->oldname)->put(['name'=> $request->newname]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categories = Category::findOrFail($id);
        $categories->delete();
    }

    public function dataTable()
    {
        $categories = Category::all();
        return DataTables()->of($categories)
            ->addColumn('action', function ($categories) {
                return view('layouts.action', [
                    'categories' => $categories,
                    'url_edit' => route('category.edit', $categories->id),
                    'url_destroy' => route('category.destroy', $categories->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
