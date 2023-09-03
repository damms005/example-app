<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::paginate(5);

        return view('users.index', [
            'users' => $users,
        ]);
    }
}
