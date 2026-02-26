<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ColocationUser;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $membership = ColocationUser::where('user_id', $userId)->first();

        if ($membership) {
            return redirect('/colocation/' . $membership->colocation_id);
        }
        $users = User::all();

        return view('dashboard', compact('users'));
    }
}
