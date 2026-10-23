<?php

namespace App\Models\Traits;

use App\Models\Product;

trait ProductTraits {
    private $_product = null;
    function product(): Product{
        if($this->_product === null)
            $this->_product = Product::find($this->product_id);
        return $this->_product;
    }
}
