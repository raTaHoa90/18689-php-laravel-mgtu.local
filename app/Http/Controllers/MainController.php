<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class MainController extends BaseController
{
    function __construct(){
        parent::__construct();

        view()->share('countCartProducts', count(Session::get('products',[])));
    }

    function breadcrumbs(?Category $category){
        $categories = collect();

        while($category){
            $categories->unshift($category);
            $category = $category->parent();
        }

        $this->breadcrumbs->AddMenu('На главную')->link('/')->icon('fa-home');
        foreach($categories as $category)
            $this->breadcrumbs->AddMenu($category->caption)->link('/categories/'.$category->saf);
    }

    function getCategoriesID(Category $category, Collection $categories){
        foreach($category->children() as $subCategory){
            $categories->push($subCategory->id);
            $this->getCategoriesID($subCategory, $categories);
        }
    }

    /// GET


    function main(){
        return view('main.main',[
            'products' => Product::where('status', Product::STATUS_VISIBLE)->orderBy('id')->paginate(10)
        ]);
    }

    function product(Product $product){
        $this->breadcrumbs($product->category());

        return view('main.product', [
            'product' => $product
        ]);
    }

    function category(Category $category){
        $categoriesID = collect([$category->id]);
        $this->getCategoriesID($category, $categoriesID);

        $this->breadcrumbs($category->parent());

        return view('main.category', [
            'category' => $category,
            // SELECT * FROM products where status = 1 AND category_id in (?,...) ORDER BY id LIMIT 10 OFFSET ?
            'products' => Product::where('status', Product::STATUS_VISIBLE)
                ->whereIn('category_id', $categoriesID)
                ->orderBy('id')
                ->paginate(10)
        ]);
    }
}
