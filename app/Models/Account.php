<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'user_id',
        'name',
        'balance',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
