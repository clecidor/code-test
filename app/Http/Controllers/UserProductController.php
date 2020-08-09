<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->user()->products()->get();
        return $request->user()->load(['products']);
    }

    public function show(Product $product)
    {
        return $product->load(['user'])->refresh();
    }

    public function associateProduct(Request $request, Product $product)
    {
        $user = $request->user();
        $product->user()->associate($user)->save();
        return $product->refresh();
    }

    public function dissociateProduct(Product $product)
    {
        $product->user()->dissociate()->save();
        return $product->refresh();
    }
}
