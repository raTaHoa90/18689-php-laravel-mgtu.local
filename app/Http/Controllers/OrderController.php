<?php

namespace App\Http\Controllers;

use App\Mail\OrderProductsMail;
use App\Models\OrderProduct;
use App\Models\OrderRecord;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OrderController extends BaseController {

    function _updateProductSession(int $idProduct, callable $func): string{
        $products = Session::get('products', []);

        $products = $func($products, $idProduct);

        Session::put('products', $products);

        return '{"ok":true, "count":'.count($products).'}';
    }


    ///  GET

    function cartPage(){
        $this->rightMenu->active = 'carts';
        $ordersProduct = Session::get('products', []);

        $sum = 0;
        foreach($ordersProduct as $idProduct => &$orderProduct){
            $orderProduct['product'] = Product::find($idProduct);
            if($orderProduct['product']){
                $orderProduct['price'] = $orderProduct['product']->price * $orderProduct['count'];
                $sum += $orderProduct['price'];
            }
        }


        return view('main.cart_page',[
            'ordersProduct' => $ordersProduct,
            'totalSum' => $sum,
            'countCartProducts' => count($ordersProduct)
        ]);
    }

    /*** POST ***/
    function addOrder(){
        request()->validate([
            'products.*.count' => 'integer',
            'totalSum' => 'required|integer',
            'email' => 'required',
            'tel' => ['regex:/(\+7|8) ?\(?\d{3}\)? ?\d{3}-?\d{2}-?\d{2}/']
        ]);

        Session::remove('products');

        $reqProducts = request('products', []);
        $email = request('email');

        $order = OrderRecord::create([
            'status' => OrderRecord::STATUS_NEW_ORDER, // статус заказа
            'count_products' => count($reqProducts),   // общее кол-во товара
            'sum_price' => request('totalSum', 0),     // общая сумма заказа
            'email' => $email,                         // почта покупателя
            'tel' => request('tel', ''),               // Телефон пользователя
            'address' => request('address', ''),       // адресс доставки
            'other' => request('other', '')
        ]);

        foreach($reqProducts as $id => $prodAr)
            OrderProduct::create([
                'order_id' => $order->id,      // ссылка на заказ
                'product_id' => $id,    // ссылка на товар
                'count_product' => $prodAr['count'], // количество этого товара
                'param_values' => $prodAr['params'] ?? ''
            ]);

        Mail::to($email)->send(new OrderProductsMail($order));


        return redirect('/');
    }

    function incProductToCart(){
        request()->validate([
            'idProduct' => 'required|integer'
        ]);

        return $this->_updateProductSession(request('idProduct'), function($products, $idProduct){
            if(isset($products[$idProduct]))
                $products[$idProduct]['count']++;
            return $products;
        });
    }

    function decProductToCart(){
        request()->validate([
            'idProduct' => 'required|integer'
        ]);

        return $this->_updateProductSession(request('idProduct'), function($products, $idProduct){
            if(isset($products[$idProduct]))
                $products[$idProduct]['count']--;
            if($products[$idProduct]['count'] <= 0)
                unset($products[$idProduct]);
            return $products;
        });
    }

    function setProductToCart(){
        request()->validate([
            'idProduct' => 'required|integer',
            'value' => 'required|integer'
        ]);

        return $this->_updateProductSession(request('idProduct'), function($products, $idProduct){
            if(isset($products[$idProduct]))
                $products[$idProduct]['count'] = (int)request('value');
            if($products[$idProduct]['count'] <= 0)
                unset($products[$idProduct]);
            return $products;
        });
    }

    /*** POST AJAX ***/
    function addProductToCart(){
        request()->validate([
            'idProduct' => 'required|integer'
        ]);

        return $this->_updateProductSession(request('idProduct'), function($products, $idProduct){
            if(isset($products[$idProduct]))
                $products[$idProduct]['count']++;
            else
                $products[$idProduct] = [
                    'count' => 1,
                    'params' => request('params',[])
                ];
            return $products;
        });
    }
}
