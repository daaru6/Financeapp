<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'sub_category_name', 'user_id'];

    protected static function booted()
    {
        // Apply global scope to filter categories by current user or where user_id is null
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhereNull('user_id');
            });
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isDefault()
    {
        return is_null($this->user_id);
    }
}
