<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_name',
    ];

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

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class, "category_id", "id");
    }

    public function isDefault()
{
    return is_null($this->user_id);
}

}
