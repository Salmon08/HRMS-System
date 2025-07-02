<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
         $employees = User::with('roles')->latest()->get()->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'department' => $user->department,
            'position' => $user->position,
            'join_date' => $user->join_date,
            'status' => $user->status,
            'role_name' => $user->roles->pluck('name')->implode(', '),
        ];
    });

    return Inertia::render('Employees/Index', [
        'employees' => $employees,
        'roles' => Role::all(),
    ]);
    }

    public function create()
    {
        return Inertia::render('Employees/Form', [
            'roles' => Role::all(),
            'employee' => null
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', 'email' => 'required|email|unique:users',
            'phone' => 'required', 'department' => 'required',
            'position' => 'required', 'join_date' => 'required|date',
            'status' => 'required', 'password' => 'required|min:6',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name, 'email' => $request->email,
            'phone' => $request->phone, 'department' => $request->department,
            'position' => $request->position, 'join_date' => $request->join_date,
            'status' => $request->status,
            'password' => Hash::make($request->password)

        ]);

        $user->assignRole($request->role);

        return response()->json(['message' => 'Employee created']);
    }

   public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $user->role_name = $user->roles->pluck('name')->first();

        return Inertia::render('Employees/Form', [
            'employee' => $user,
            'roles' => Role::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required', 'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required', 'department' => 'required',
            'position' => 'required', 'join_date' => 'required|date',
            'status' => 'required',
             'role' => 'required|exists:roles,name'
        ]);

        $user->update($request->only([
            'name', 'email', 'phone', 'department',
            'position', 'join_date', 'status'
        ]));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return response()->json(['message' => 'Employee updated']);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Employee deleted']);
    }
}
