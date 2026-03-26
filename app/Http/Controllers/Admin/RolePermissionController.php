<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RolePermissionController extends Controller
{
    use AuthorizesRequests;
    // ════════════════════════════════════
    //  RÔLES
    // ════════════════════════════════════

    public function rolesIndex()
    {
        $this->authorize('assign roles');
        $roles = Role::withCount('users', 'permissions')->get();
        $permissions = Permission::all()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'general');
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function rolesStore(Request $request)
    {
        $this->authorize('assign roles');
        $request->validate(['name' => 'required|string|unique:roles,name']);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('success', "Rôle « {$role->name} » créé.");
    }

    public function rolesUpdate(Request $request, Role $role)
    {
        $this->authorize('assign roles');
        $request->validate(['permissions' => 'array']);

        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', "Permissions du rôle « {$role->name} » mises à jour.");
    }

    public function rolesDestroy(Role $role)
    {
        $this->authorize('assign roles');

        $protected = ['super-admin', 'admin', 'student'];
        if (in_array($role->name, $protected)) {
            return back()->with('error', "Le rôle « {$role->name} » est protégé.");
        }

        $role->delete();
        return back()->with('success', "Rôle supprimé.");
    }

    // ════════════════════════════════════
    //  PERMISSIONS
    // ════════════════════════════════════

    public function permissionsIndex()
    {
        $this->authorize('manage platform');
        $permissions = Permission::all()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'general');
        return view('admin.permissions.index', compact('permissions'));
    }

    public function permissionsStore(Request $request)
    {
        $this->authorize('manage platform');
        $request->validate(['name' => 'required|string|unique:permissions,name']);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Permission « {$request->name} » créée.");
    }

    public function permissionsDestroy(Permission $permission)
    {
        $this->authorize('manage platform');
        $permission->delete();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        return back()->with('success', "Permission supprimée.");
    }
}
