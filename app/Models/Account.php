<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'user_id',
        'name',
    ];

    protected static function booted()
    {
        // Apply global scope to filter categories by current user
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(AccountLog::class, "account_id", "id")->latest();
    }

    public function getCurrentMonthLogs()
    {
        $currentMonthStart = now()->startOfMonth();
    
        return $this->logs()->whereDate('created_at', '>=', $currentMonthStart)->get();
    }

    public function createLog($balance)
    {
        $currentMonth = now()->startOfMonth()->format('Y-m-d');

        return $this->logs()->create([
            'balance' => $balance,
        ]);
    }
}
