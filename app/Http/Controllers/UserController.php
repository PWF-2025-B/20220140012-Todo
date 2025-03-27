<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function index(){

        $users = User::all(); // Ambil semua data user
        dd($users);
        return view('user.index', compact('users'));

}

}
