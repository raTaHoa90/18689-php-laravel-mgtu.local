<?php

namespace App\Models;

use App\Models\Traits\ProductTraits;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'product_id', // Ссылка на товар
    'user_id',    // Ссылка на пользователя
    'rating',    // число поставленных звезд
    'message'     // комментарий к рейтингу
])]
class ProductRating extends Model {
    use ProductTraits;
    protected $table = 'product_rating';

    #[Scope]
    protected function avgStars(Builder $query, Product $product){
        $query->where('product_id', $product->id);
    }
}
