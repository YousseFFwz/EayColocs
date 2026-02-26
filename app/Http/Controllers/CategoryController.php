<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\ColocationUser;

class CategoryController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $pivot = ColocationUser::where('colocation_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pivot || $pivot->role !== 'owner') {
            return back()->with('error', 'Unauthorized');
        }

        Category::create([
            'name' => $request->name,
            'colocation_id' => $id
        ]);

        return back()->with('success', 'Category added');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return back()->with('success', 'Category deleted');
    }
}