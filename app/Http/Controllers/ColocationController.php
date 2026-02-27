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

    $user = Auth::user();

    if ($user->colocations()->exists()) {
        return redirect('/dashboard')
            ->with('error', 'You already have a colocation.');
    }

    $colocation = Colocation::create([
        'name' => $request->name,
        'description' => $request->description,
        'status' => 'active'
    ]);

    $colocation->users()->attach($user->id, [
        'role' => 'owner',
        'joined_at' => now()
    ]);

    return redirect('/colocation/' . $colocation->id);
}





public function show($id)
    {
        $colocation = Colocation::with([
            'users',
            'expenses.user',
            'expenses.category',
            'payments'
        ])->findOrFail($id);

        $currentUserId = Auth::id();

        $pivot = ColocationUser::where('colocation_id', $id)
            ->where('user_id', $currentUserId)
            ->first();

        if (!$pivot) {
            return redirect('/dashboard')
                ->with('error', 'Access denied');
        }

        $total = 0;
        $balances = [];
        $transactions = [];

        $membersCount = $colocation->users->count();

        if ($membersCount > 0) {

            $total = $colocation->expenses->sum('amount');
            $part = $total / $membersCount;

            foreach ($colocation->users as $user) {

                $paidExpenses = $colocation->expenses
                    ->where('user_id', $user->id)
                    ->sum('amount');

                $paymentsSent = $user->paymentsSent()
                    ->where('colocation_id', $id)
                    ->sum('amount');

                $paymentsReceived = $user->paymentsReceived()
                    ->where('colocation_id', $id)
                    ->sum('amount');

                $paid = $paidExpenses + $paymentsReceived - $paymentsSent;

                $balance = $paid - $part;

                $balances[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'balance' => $balance
                ];
            }


            $creditors = [];
            $debtors = [];

            foreach ($balances as $b) {
                if ($b['balance'] > 0) {
                    $creditors[] = [
                        'user_id' => $b['user_id'],
                        'name' => $b['name'],
                        'amount' => $b['balance']
                    ];
                } elseif ($b['balance'] < 0) {
                    $debtors[] = [
                        'user_id' => $b['user_id'],
                        'name' => $b['name'],
                        'amount' => abs($b['balance'])
                    ];
                }
            }

            $i = 0;
            $j = 0;

            while ($i < count($debtors) && $j < count($creditors)) {

                $payAmount = min(
                    $debtors[$i]['amount'],
                    $creditors[$j]['amount']
                );

                $transactions[] = [
                    'from_id' => $debtors[$i]['user_id'],
                    'from' => $debtors[$i]['name'],
                    'to_id' => $creditors[$j]['user_id'],
                    'to' => $creditors[$j]['name'],
                    'amount' => $payAmount
                ];

                $debtors[$i]['amount'] -= $payAmount;
                $creditors[$j]['amount'] -= $payAmount;

                if ($debtors[$i]['amount'] == 0) {
                    $i++;
                }

                if ($creditors[$j]['amount'] == 0) {
                    $j++;
                }
            }
        }

        return view('colocation.show', compact(
            'colocation',
            'pivot',
            'total',
            'balances',
            'transactions'
        ));
    }








public function quit($id)
{
    $user = Auth::user();

    $colocation = Colocation::with(['users','expenses'])->findOrFail($id);

    $membership = ColocationUser::where('colocation_id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $membersCount = $colocation->users->count();

    $total = $colocation->expenses->sum('amount');

    $share = $membersCount > 0 ? $total / $membersCount : 0;



    $paidExpenses = $colocation->expenses
    ->where('user_id', $user->id)
    ->sum('amount');

    $paymentsSent = $user->paymentsSent
    ->where('colocation_id', $id)
    ->sum('amount');

    $paymentsReceived = $user->paymentsReceived
    ->where('colocation_id', $id)
    ->sum('amount');

    $paid = $paidExpenses + $paymentsReceived - $paymentsSent;

    $balance = round($paid - $share, 2);

    



    if ($membership->role === 'owner') {

        if ($balance < 0) {
            return back()->with('error', 'You must settle your balance before cancelling.');
        }

        $colocation->delete();

        return redirect('/dashboard')
            ->with('success', 'Colocation cancelled.');
    }

   

    
    if ($balance < 0) {
        $user->decrement('reputation');
    } else {
        $user->increment('reputation');
    }

    $membership->delete();

    return redirect('/dashboard')
        ->with('success', 'You left the colocation.');
  }
}
