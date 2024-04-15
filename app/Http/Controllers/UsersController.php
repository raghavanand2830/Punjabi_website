<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class  UsersController extends Controller
{
    public function index()
    {
        $users = User::where('role_id',1)->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'DOB' => 'required|date',
            'role_id' => 'required|integer',
            'password' => 'required|string|min:8',
        ]);
        $user = User:: where('email',$request->email)->first();
        if($user){
         return redirect()->back()->with('alert', 'Account with email already exist');
        }
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'DOB' => Carbon::parse($request->dob),
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('alert', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {

       
        // $request->validate([
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        //     'phone' => 'nullable|string|max:20',
        //     'DOB' => 'required|date',
        //     'role_id' => 'required|integer',
        //     'password' => 'nullable|string|min:8',
        // ]);
      
       
        $user->update([
            'first_name' => $request->first_name ? $request->first_name: $user->first_name ,
            'last_name' => $request->last_name ? $request->last_name: $user->last_name,
            'email' => $request->email ? $request->email: $user->email,
            'phone' => $request->phone ? $request->phone: $user->phone,
            'dob' => $request->dob ? $request->dob: $user->dob,
            'role_id' => $request->role_id ? $request->role_id: $user->role_id,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);
       
        return redirect()->route('users.index')
            ->with('alert', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}