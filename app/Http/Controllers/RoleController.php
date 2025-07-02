<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        // If it's an API request (axios/fetch), return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'roles' => $roles,
                'permissions' => $permissions,
            ]);
        }

        return Inertia::render('Roles/Role', [
            'roles' =>Role::with('permissions')->get(),
            'permissions' => Permission::all(),
        ]);

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' =>'required|unique:roles,name',
            'permissions' =>'array|required',
            'permissions.*' =>'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name']
        ]);
        $role->syncPermissions($validated['permissions']);

        return response()->json(['message' => 'Role created successfully.']);

    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' .$id,
            'permissions' => 'array|required',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return response()->json(['message' => 'Role updated successfully.']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully.']);
    }
}
