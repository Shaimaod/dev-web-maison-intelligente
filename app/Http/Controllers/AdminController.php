<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10); // Pagination ici

        return view('admin.dashboard', compact('users', 'search'));
    }
 
    public function promote($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Utilisateur promu avec succès.');
    }

    public function updateRole(Request $request, $id)
    {
        if (Auth::id() == $id) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return back()->with('success', 'Rôle mis à jour avec succès.');
    }

    public function deleteUser($id)
    {
        if (Auth::id() == $id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

}
