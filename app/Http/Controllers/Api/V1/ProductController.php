<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\V1\StoreProductRequest;
use App\Http\Requests\V1\UpdateProductRequest;

use App\Http\Resources\V1\ProductCollection;
use App\Http\Resources\V1\ProductResource;

use App\Filters\V1\ProductsFilter;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $productsFilter = new ProductsFilter();

            $queryItems = $productsFilter->transform($request); // [['column', 'operator', 'value']]

            $includeImages = $request->query('images', true);
            $includeDiscount = $request->query('discount', true);

            $products = Product::where($queryItems)->orderBy('id', 'desc');

            if ($includeImages == 'true') {
                $products->with('images');
            }

            if ($includeDiscount == 'true') {
                $products->with('discount');
            }

            if( ! $products->count() ) return response()->json(['message' => 'No products found'], 404);

            return new ProductCollection($products->paginate()->appends($request->query()));

        } catch (\Exception $e) {

            \Log::error('Error fetching products: ' . $e->getMessage());

            return response()->json('Error fetching products: '.$e->getMessage() , 500);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {

        try {

            $product = Product::where('slug', $slug)->with('images', 'discount')->first();

            if( ! $product ) return response()->json(['message' => 'Product not found'], 404);

            $includeImages = request()->query('images', true);
            $includeDiscount = request()->query('discount', true);

            if($includeImages != 'true') {
                $product->unsetRelation('images');
            }

            if($includeDiscount != 'true') {
                $product->unsetRelation('discount');
            }

            return new ProductResource($product);

        } catch (\Exception $e) {

            \Log::error('Error showing product: ' . $e->getMessage());

            return response()->json('Error showing products: '.$e->getMessage() , 500);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\V1\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try {

            $product = Product::create($request->all());
            $product->discount()->create($request->all());
            $files = $request->file('images');
            if ($request->hasFile('images')) {
                foreach ($files as $key => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create(['path' => $path, 'product_id' => $product->id]);
                }
            }

            return new ProductResource($product->loadMissing('images', 'discount'));

        } catch (\Exception $e) {

            \Log::error('Error storing product: ' . $e->getMessage());

            return response()->json('Error storing products: '.$e->getMessage() , 500);

        }
    }

     /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\V1\UpdateProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {

            $product = Product::where('slug', $slug)->first();

            if ( ! $product ) return response()->json(['message' => 'Product not found'], 404);

            $product->update($request->all());

            if ( !empty($request->discount ) ) {

                $product->discount()->delete();
                $product->discount()->create($request->all());
            }

            if ( $request->hasFile('images') ) {

                $product->images()->delete();

                foreach ($files as $key => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create(['path' => $path, 'product_id' => $product->id]);
                }
            }

            return new ProductResource($product->loadMissing('images', 'discount'));

        } catch (\Exception $e) {

            \Log::error('Error updating product: ' . $e->getMessage());

            return response()->json('Error updating products: '.$e->getMessage() , 500);

        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if ( ! $product ) return response()->json(['message' => 'Product not found'], 404);

        try {

            $product->discount()->delete();
            $product->images()->delete();
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully', 'status' => 200], 200);

        } catch (\Exception $e) {

            \Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting product: ' . $e->getMessage()], 500);
        }
    }
}
