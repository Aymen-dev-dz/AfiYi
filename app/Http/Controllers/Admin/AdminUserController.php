<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', "Rôle '{$request->role}' attribué à {$user->name}.");
    }

    public function toggleStatus(Request $request, User $user)
    {
        $user->update(['is_active' => ! ($user->is_active ?? true)]);

        $status = ($user->is_active ?? true) ? 'activé' : 'suspendu';

        return back()->with('success', "Utilisateur {$user->name} {$status}.");
    }
}
