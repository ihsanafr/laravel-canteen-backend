<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\table;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::query()
        ->when($request->input('name'), function($query, $name){
           return $query->where('name', 'like', "%$name%");
    })
        ->orderBy('id','desc')
        ->paginate(10);
        return view('pages.users.index', compact('users'));
    }


    public function create()
    {
        return view('pages.users.create');
    }

    public function store(request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        $data = ([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        User::create($data);

        return redirect()->route('user.index')->with('success', 'User created successfully');


    }


    public function edit($id)
    {
        $user = User::find($id);
        return view('pages.users.edit', compact('user'));
    }


    public function update(request $request, user $user)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);

        $data = ([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        if($request->password ){
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        
        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully');
    }
}

