<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des fonctionnalités d'administration
 * 
 * Ce contrôleur gère les fonctionnalités réservées aux administrateurs:
 * - Tableau de bord d'administration
 * - Gestion des utilisateurs
 * - Attribution et modification des rôles utilisateur
 * - Recherche et filtrage des utilisateurs
 */
class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord d'administration
     * avec la liste des utilisateurs et options de recherche
     * 
     * @param Request $request La requête pouvant contenir des paramètres de recherche
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10); // Pagination ici

        return view('admin.dashboard', compact('users', 'search'));
    }
 
    /**
     * Promeut un utilisateur au rang d'administrateur
     * 
     * @param int $id ID de l'utilisateur à promouvoir
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promote($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Utilisateur promu avec succès.');
    }

    /**
     * Met à jour le rôle d'un utilisateur (admin/user)
     * Empêche l'auto-rétrogradation
     * 
     * @param Request $request La requête contenant le nouveau rôle
     * @param int $id ID de l'utilisateur à modifier
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Autres méthodes d'administration...
     */
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
