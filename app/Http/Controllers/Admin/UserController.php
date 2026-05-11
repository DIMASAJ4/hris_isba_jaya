<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.settings.index', compact('users', 'roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        if ($request->role === 'member') {
            Member::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'status' => 'Pending',
                // member_code will be auto-generated
                'nim' => 'TEMP-' . time(), // Temporary NIM
            ]);
        }

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset.');
    }

    public function updateRoles(Request $request)
    {
        $roles = Role::all();
        foreach ($roles as $role) {
            // Ambil ID permission dari request
            $permissionIds = $request->input("permissions.{$role->id}", []);
            
            // Cari objek Permission berdasarkan ID
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            
            // Sinkronisasi menggunakan objek Permission
            $role->syncPermissions($permissions);
        }

        return redirect()->back()->with('success', 'Hak akses role berhasil diperbarui.');
    }
}
