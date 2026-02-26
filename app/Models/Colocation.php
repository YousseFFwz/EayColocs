<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
            'name',
            'description',
            'status'
      ];


      public function users()
{
    return $this->belongsToMany(User::class, 'colocation_user')
                ->withPivot('role', 'joined_at');
}

public function expenses()
{
    return $this->hasMany(Expense::class);
}

public function categories()
{
    return $this->hasMany(Category::class);
}

public function payments()
{
    return $this->hasMany(Payment::class);
}
}
