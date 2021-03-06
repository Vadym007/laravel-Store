<?php

namespace App\UseCases\Store;

use App\Http\Requests\Store\Products\Ajax\SetSizeRequest;
use App\Http\Requests\Store\Products\Ajax\SetColorRequest;
use App\Entity\Store\Product\Size;
use App\Entity\Store\Characteristics\Size as StoreSize;
use App\Entity\Store\Product\ProductVariant;
use App\Entity\Store\Characteristics\Color;
use Illuminate\Http\Request;
use App\Entity\Store\Product\Product;
use App\Entity\Store\Provider\Provider;
use App\Http\Requests\Store\Products\Ajax\SetCategoryRequest;
use App\Entity\Store\Category;
use App\Http\Requests\Store\Products\Ajax\UpdateNameRequest;
use App\Http\Requests\Store\Products\Ajax\UpdatePriceRequest;
use App\Http\Requests\Store\Products\Ajax\UpdateDescriptionRequest;


class ProductService
{
    public function setSize (SetSizeRequest $request)
    {
        $data = $request->validated();

        $size = Size::findOrFail($data['sizeId']);
        $storeSize = StoreSize::findOrFail($data['storeSizeId']);

        $size->storeSize()->associate($storeSize)->save();   
        return $size;  
    }

    public function setColor(SetColorRequest $request)
    {
        $data = $request->validated();
        $variant = ProductVariant::findOrFail($data['variantId']);
        $color = Color::findOrFail($data['colorId']);
        $variant->color()->associate($color)->save();   
        return $variant;
    }

    public function setCategory(SetCategoryRequest $request)
    {
        $data = $request->validated();
        $product = Product::findOrFail($data['productId']);
        $category = Category::findOrFail($data['categoryId']);

        $product->category()->associate($category)->save();

        return $product;

    }

    public function updateName(UpdateNameRequest $request)
    {
        $data = $request->validated();
        $product = Product::findOrFail($data['productId']);
        $product->update(['name' => $data['name']]);
        return $product;
    }

    public function updatePrice(UpdatePriceRequest $request)
    {
        $data = $request->validated();
        $variant = ProductVariant::findOrFail($data['variantId']);
        $variant->update(['price' => $data['price']]);
        return $variant;
    }

    public function updateDescription(UpdateDescriptionRequest $request)
    {
        $data = $request->validated();
        $variant = ProductVariant::findOrFail($data['variantId']);
        $variant->update(['description' => $data['description']]);
        return $variant;
    }

    public function getWithFilters(Request $request)
    {
        $filtres = $request->all();
        $products = Product::select('products.*');

        if(array_key_exists('categories', $filtres)) {
            $categories = Category::whereIn('id', $filtres['categories'])->get();
            $categoriesWithChildren = [];

            foreach ($categories as $category) {
                $categoriesWithChildren = array_merge($category->descendantsId(), $categoriesWithChildren);
            }
            
            $products = $products->whereIn('products.category_id',  $categoriesWithChildren);
        }

        if(array_key_exists('providers', $filtres)) {
            $products = $products->whereIn('products.provider_id', $filtres['providers']);
        }

        if(array_key_exists('name', $filtres) && !empty($filtres['name'])) {
            $products = $products->where('products.name', 'like', '%' . $filtres['name'] . '%');
        }

        if (
                array_key_exists('code', $filtres) && !empty($filtres['code']) || 
                array_key_exists('colors', $filtres) || 
                array_key_exists('sizes', $filtres) ||
                array_key_exists('without-color', $filtres) ||
                array_key_exists('without-size', $filtres) 
            ) { 
            $products = $products->join('products_variations', 'products_variations.product_id', '=', 'products.id');      
        }

        if( array_key_exists('sizes', $filtres) ||  array_key_exists('without-size', $filtres)) {
            $products->join('sizes_product_variations', 'products_variations.id', '=', 'sizes_product_variations.variation_id');
        }

        if( array_key_exists('code', $filtres) && !empty($filtres['code']) ) {
            $products = $products->where('products_variations.code', $filtres['code']);
        }

        if( array_key_exists('colors', $filtres) ) {
            $products = $products->whereIn('products_variations.color_id', $filtres['colors']);
        }

        if( array_key_exists('sizes', $filtres) ) {
            $products = $products->whereIn('sizes_product_variations.size_id', $filtres['sizes']);
        }

        if(array_key_exists('without-category', $filtres)) {
            $products = $products->whereNull('products.category_id');
        }

        if(array_key_exists('without-color', $filtres)) {
            $products = $products->whereNull('products_variations.color_id');
        }

        if(array_key_exists('without-size', $filtres)) {
            $products = $products->whereNull('sizes_product_variations.size_id');
        }

        $products = $products
                    ->orderBy('id', 'desc')
                    ->with('variants')
                    ->groupBy('products.id')
                    ->paginate('20');

        return $products;
    }

    public function deleteByProvider(Provider $provider)
    {
        $products = Product::where('provider_id', $provider->id)->get();

        \DB::transaction(function () use ($products) {
            foreach($products as $product) {

                foreach($product->variants as $variant){
                   
                    foreach($variant->photos as $photo) {
                        $photo->delete();
                    }

                    foreach($variant->sizes as $size){
                        $size->delete();
                    }

                    $variant->delete();

                }

                $product->delete();
            }
        });

        return true;
    }
}