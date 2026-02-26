<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use App\Models\ColocationUser;

class ExpenseController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string'
        ]);

        $membership = ColocationUser::where('colocation_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$membership) {
            return back()->with('error', 'Unauthorized');
        }

        Expense::create([
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'colocation_id' => $id,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Expense added successfully');
    }
}