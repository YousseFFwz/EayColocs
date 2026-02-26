<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ColocationUser;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $membership = ColocationUser::where('user_id', $userId)->first();

        if ($membership) {
            return redirect('/colocation/' . $membership->colocation_id);
        }

        return view('dashboard');
    }
}
