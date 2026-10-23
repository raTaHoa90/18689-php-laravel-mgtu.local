<?php

namespace App\Models;

use App\Models\Traits\ProductTraits;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'order_id',      // ссылка на заказ
    'product_id',    // ссылка на товар
    'count_product', // количество этого товара
    'param_values'   // внесенные параметры товара для заказа
])]
class OrderProduct extends Model {
    use ProductTraits;

    public $timestamps = false;
    protected $table = 'order_product';


    // Аксессор - несуществующие поля
    // $this->params
    function params(): Attribute {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => json_decode($attributes['param_values'] ?: '[]', true)
        );
    }

    /*
    // Мутатор - существующие поля
    // $this->param_values
    function paramValues(): Attribute {
        return Attribute::make(
            // return json_decode($this->param_values ?: '[]', true)
            get: fn(string $value) => json_decode($value ?: '[]', true),
            // $this->param_values = json_encode($value)
            set: fn(mixed $value) => json_encode($value)
        );
    }
    */
}
