<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

class ProductController extends Controller
{
    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Product[]
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Product
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(Product::validatorRules());

        /** @var Product $product */
        $product = Product::make($validatedData);

        if ($user = $request->user()) {
            $product->user()->associate($user);
        }

        $product->save();

        return $product->refresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return Product
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product  $product
     * @return Product
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate(Product::validatorRules());
        $product->fill($validatedData);
        $product->save();

        return $product->refresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return array [deleted => Product]
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return [
                'deleted' => $product
            ];
        }
    }

    public function image(Request $request, Product $product)
    {

    }
}
