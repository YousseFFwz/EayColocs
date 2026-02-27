<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ColocationUser;
use App\Models\User;


class DashboardController extends Controller
{
 public function index()
{
    $user = Auth::user();

    $colocations = $user->colocations;
    $users = User::all();
    return view('dashboard', compact('colocations', 'users'));
}


}
