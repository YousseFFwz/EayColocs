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





public function show($id)
    {
        // 1️⃣ جلب colocation مع العلاقات
        $colocation = Colocation::with([
            'users',
            'expenses.user',
            'expenses.category',
            'payments'
        ])->findOrFail($id);

        // 2️⃣ جلب ID المستخدم الحالي
        $currentUserId = Auth::id();

        // 3️⃣ التحقق هل المستخدم عضو في هذه colocation
        $pivot = ColocationUser::where('colocation_id', $id)
            ->where('user_id', $currentUserId)
            ->first();

        if (!$pivot) {
            return redirect('/dashboard')
                ->with('error', 'Access denied');
        }

        // 4️⃣ تهيئة المتغيرات
        $total = 0;
        $balances = [];
        $transactions = [];

        $membersCount = $colocation->users->count();

        if ($membersCount > 0) {

            // 5️⃣ حساب مجموع المصاريف
            $total = $colocation->expenses->sum('amount');
            $part = $total / $membersCount;

            foreach ($colocation->users as $user) {

                // شحال خلّص فالمصاريف
                $paidExpenses = $colocation->expenses
                    ->where('user_id', $user->id)
                    ->sum('amount');

                // شحال صيفط
                $paymentsSent = $user->paymentsSent()
                    ->where('colocation_id', $id)
                    ->sum('amount');

                $paymentsReceived = $user->paymentsReceived()
                    ->where('colocation_id', $id)
                    ->sum('amount');

                // شحال ساهم فعلياً
                $paid = $paidExpenses + $paymentsReceived - $paymentsSent;

                // الرصيد النهائي
                $balance = $paid - $part;

                $balances[] = [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'balance' => $balance
                ];
            }

            // ===== حساب شكون يخلّص شكون =====

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

        // 6️⃣ إرسال البيانات للـ view
        return view('colocation.show', compact(
            'colocation',
            'pivot',
            'total',
            'balances',
            'transactions'
        ));
    }


}
