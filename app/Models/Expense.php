<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
    'amount',
    'description',
    'date',
    'user_id',
    'category_id',
    'colocation_id'
];

// 🔥 Expense kaynتمي ل User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 Expense kaynتمي ل Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🔥 Expense kaynتمي ل Colocation
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    
}
