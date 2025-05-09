<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedUser;
use Illuminate\Http\Request;

class AuthorizedUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $authorizedUsers = AuthorizedUser::paginate(10);
        return view('admin.authorized_users.index', compact('authorizedUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:authorized_users,email'
        ], [
            'email.unique' => 'Cette adresse email est déjà autorisée.',
        ]);

        try {
            AuthorizedUser::create(['email' => $request->email]);
            return redirect()->back()->with('success', 'Email ajouté à la liste autorisée.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout de l\'email: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            AuthorizedUser::destroy($id);
            return redirect()->back()->with('success', 'Email supprimé de la liste.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }
}
