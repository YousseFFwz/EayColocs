<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
            {
                $users = \App\Models\User::all();
                return view('dashboard', compact('users'));
            }
}
