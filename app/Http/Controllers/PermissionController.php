<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::all();

        // Return JSON if Axios request, else render Inertia page
        if ($request->wantsJson()) {
            return response()->json(['permissions' => $permissions]);
        }

        return Inertia::render('Permissions/Permissions', [
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $validated['name']]);

        return response()->json(['message' => 'Permission created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name,'. $permission->id,
        ]);

        $permission->update(['name' => $validated['name']]);

        return response()->json(['message'=> 'permission updated successfully.']);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrfail($id);
        $permission->delete();

        return response()->json(['message' =>'permission deleted successfully.']);
    }

}
