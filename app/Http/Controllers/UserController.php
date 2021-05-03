<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
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
        $model = new User();
        $model['roles'] = Role::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        return view('pages.user.formuser', ['model' => $model]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users|max:255',
            'phone_number' => 'required|numeric',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assignRole($request->input('role'));
    }

    public function edit($id)
    {
        $model = User::findOrFail($id);
        $model['roles'] = Role::all()->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->pluck('name', 'id');
        return view('pages.user.formuser', ['model' => $model]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone_number' => 'required|numeric',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6'
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->syncRoles($request->input('role'));
        $user->save();
    }

    public function destroy($id)
    {
        $users = User::findOrFail($id);
        $users->delete();
    }

    public function DataTable()
    {
        $users = User::where(function ($query) {
            $query->whereHas('roles', function ($query) {
                return $query->where('name', '!=', 'members');
            });
        })->get();
        return DataTables()->of($users)
            ->addColumn('action', function ($users) {
                return view('pages.user.useraction', [
                    'users' => $users,
                    'url_edit' => route('user.edit', $users->id),
                    'url_destroy' => route('user.destroy', $users->id)
                ]);
            })
            ->addColumn('role', function ($users) {
                return $users->roles()->pluck('name')->toArray();
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'role'])
            ->make(true);
    }
}
