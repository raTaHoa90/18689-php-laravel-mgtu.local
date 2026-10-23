<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MainController extends BaseController
{

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
        //avg_rating
        return view('main.main',[
            'products' => Product::query()->visibleStatus()->paginate(10)
        ]);
    }

    function product(Product $product){
        $this->breadcrumbs($product->category());

        $rating = null;
        $user = Auth::user();
        if($user)
            $rating = ProductRating::where([
                'product_id' => $product->id,
                'user_id' => $user->id
            ])->first();

        return view('main.product', [
            'product' => $product,
            'rating' => $rating,
            'avgRating' => number_format(ProductRating::query()->avgStars($product)->avg('rating'), 2)
        ]);
    }

    function category(Category $category){
        $categoriesID = collect([$category->id]);
        $this->getCategoriesID($category, $categoriesID);

        $this->breadcrumbs($category->parent());

        return view('main.category', [
            'category' => $category,
            // SELECT * FROM products where status = 1 AND category_id in (?,...) ORDER BY id LIMIT 10 OFFSET ?
            'products' => Product::whereIn('category_id', $categoriesID)
                ->visibleStatus()
                ->paginate(10)
        ]);
    }

    // AJAX-POST

    function ratingProduct(){
        request()->validate([
            'idProduct' => 'required|integer',
            'stars' => 'required|integer|between:1,5'
        ]);

        $user = Auth::user();
        if(!$user) return '{"error":"Необходимо авторизоваться"}';


        ProductRating::updateOrCreate([
            'product_id' => request('idProduct'),
            'user_id' => $user->id
        ],[
            'rating' => request('stars')
        ]);

        return '{"ok":true}';
    }
}
