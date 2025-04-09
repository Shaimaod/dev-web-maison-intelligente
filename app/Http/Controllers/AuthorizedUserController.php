<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedUser;
use Illuminate\Http\Request;

class AuthorizedUserController extends Controller
{
    public function index()
    {
        $authorizedUsers = AuthorizedUser::paginate(10);
        return view('admin.authorized_users.index', compact('authorizedUsers'));
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:authorized_users,email']);
        AuthorizedUser::create(['email' => $request->email]);

        return redirect()->back()->with('success', 'Email ajouté à la liste autorisée.');
    }

    public function destroy($id)
    {
        AuthorizedUser::destroy($id);
        return redirect()->back()->with('success', 'Email supprimé de la liste.');
    }
}
