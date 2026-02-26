<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Models\ColocationUser;

class InvitationController extends Controller
{
    public function generate($id)
    {
        $token = Str::random(40);

        Invitation::create([
            'token' => $token,
            'colocation_id' => $id,
            'status' => 'pending'
        ]);

        $link = url('/invite/' . $token);

        return back()->with('success', $link);
    }

    public function join($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (!Auth::check()) {

            session(['invite_token' => $token]);

            return redirect('/login');
        }

        $user = Auth::user();

        ColocationUser::firstOrCreate([
            'user_id' => $user->id,
            'colocation_id' => $invitation->colocation_id,
        ], [
            'role' => 'member',
            'joined_at' => now()
        ]);

        return redirect('/colocation/' . $invitation->colocation_id);
    }
}