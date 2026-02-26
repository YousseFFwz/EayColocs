<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colocation;
use App\Models\ColocationUser;
use Illuminate\Support\Facades\Auth;

class ColocationController extends Controller
{


public function create()
{
 return view('colocation.create');
}


public function store(Request $request)
{
    
    $request->validate([
        'name' => 'required'
    ]);

    $colocation = Colocation::create([
        'name' => $request->name,
        'description' => $request->description,
        'status' => 'active'
    ]);

    
    ColocationUser::create([
        'user_id' => Auth::id(),
        'colocation_id' => $colocation->id,
        'role' => 'owner',
        'joined_at' => now()
    ]);

    return redirect('/dashboard');
}




}
