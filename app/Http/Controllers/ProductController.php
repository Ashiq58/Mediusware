<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title = $request->input('title') ?? '';
        $price_from = $request->input('price_from') ?? '';
        $price_to = $request->input('price_to') ?? '';
        $date = $request->input('date') ?? '';
        $variant = $request->input('variant') ?? '';
        $products = Product::with('inventories', 'inventories.firstVariant', 
                    'inventories.secondVariant', 'inventories.thirdVariant');
        if($title) {
            $products = $products->where('title', 'like', "%{$title}%");
        }
        if($date) {
            $fromDate = \Carbon\Carbon::parse($date)->startOfDay();
            $toDate = \Carbon\Carbon::parse($date)->endOfDay();
            $products = $products->whereBetween('created_at', [$fromDate, $toDate]);
        }
        if($price_from || $price_to || $variant) {
            $variantIds = [];
            if($variant) {
                $variants = ProductVariant::where('variant', $variant)->get();
                $variantIds = $variants->pluck('id');
            }
            $products = $products->whereHas('inventories', function($q) use ($price_from, $price_to, $variantIds)
            {
                if(count($variantIds) > 0) {
                    $q = 
                        $q->where(function($qI) use ($variantIds) {
                            $qI->whereIn('product_variant_one', $variantIds);
                        })
                        ->orWhere(function($qI) use ($variantIds) {
                            $qI->whereIn('product_variant_two', $variantIds);
                        })
                        ->orWhere(function($qI) use ($variantIds) {
                            $qI->whereIn('product_variant_three', $variantIds);
                        });
                }
                if($price_from) {
                    $q->where('price', '>=', $price_from);
                }
                if($price_to) {
                    $q->where('price', '<=', $price_to);
                }
            });
            $products = $products->with(['inventories' => function($q) use ($price_from, $price_to, $variantIds)
            {
                if(count($variantIds) > 0) {
                    $q = 
                        $q->where(function($qI) use ($variantIds) {
                            $qI->whereIn('product_variant_one', $variantIds);
                        })
                        ->orWhere(function($qI) use ($variantIds) {
                            $qI->whereIn('product_variant_two', $variantIds);
                        })
                        ->orWhere(function($qI) use ($variantIds) {
                            $qI->whereIn('product_variant_three', $variantIds);
                        });
                }
                if($price_from) {
                    $q->where('price', '>=', $price_from);
                }
                if($price_to) {
                    $q->where('price', '<=', $price_to);
                }
            }]);
        }
        $products = $products->paginate(2);
        $variants = Variant::with('productVariants')->get();
        return view('products.index', compact('products', 'variants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
