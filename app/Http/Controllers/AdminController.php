<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   

public function dashboard()
{
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    $totalUsers = User::count();
    $bannedUsers = User::where('is_banned', true)->count();
    $totalColocations = Colocation::count();
    $totalExpenses = Expense::sum('amount');

    $users = User::all();
    $colocations = Auth::user()->colocations;
    return view('admin.dashboard', compact(
        'totalUsers',
        'bannedUsers',
        'totalColocations',
        'colocations',
        'totalExpenses',
        'users'
    ));
}

public function ban($id)
{
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    $user = User::findOrFail($id);
    $user->update(['is_banned' => true]);

    return back()->with('success', 'User banned.');
}

 
}
