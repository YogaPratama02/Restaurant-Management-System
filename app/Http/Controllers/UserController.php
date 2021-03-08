<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.user.index')->with('users', $users);
    }

    public function create()
    {
        $users = new User();
        return view('pages.user.formuser', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users|max:255',
            'phone_number' => 'required|numeric',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();
        // return json_encode(true);
    }

    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('pages.user.formuser', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone_number' => 'required|numeric',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();
        // return json_encode(true);
    }

    public function destroy($id)
    {
        $users = User::findOrFail($id);
        $users->delete();
    }

    public function DataTable()
    {
        $users = User::all();
        return DataTables()->of($users)
            ->addColumn('action', function ($users) {
                return view('pages.user.useraction', [
                    'users' => $users,
                    'url_edit' => route('user.edit', $users->id),
                    'url_destroy' => route('user.destroy', $users->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
