<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\V1\ProductImageCollection;
use App\Http\Resources\V1\ProductImageResource;

use App\Models\ProductImage;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {

            $productImages = ProductImage::with('product');

            if( ! $productImages->count() ) return response()->json(['message' => 'No product images found'], 404);

            return new ProductImageCollection($productImages->paginate()->appends($request->query()));

        } catch (\Exception $e) {

            \Log::error('Error fetching product images: ' . $e->getMessage());

            return response()->json(['message' => 'Error fetching product images : ' . $e->getMessage()], 500);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function show(ProductImage $productImage)
    {
        try {

            $includeProduct = request()->query('product', true);

            if($includeProduct == 'true') {
                $productImage->loadMissing('product');
            }

            return new ProductImageResource($productImage);

        } catch (\Exception $e) {

            \Log::error('Error showing product image: ' . $e->getMessage());
            return response()->json(['message' => 'Error showing product image : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductImage $productImage)
    {

        try {

            $productImage->delete();

            return response()->json(['message' => 'Product image deleted successfully', 'status' => 200], 200);

        } catch (\Exception $e) {

            \Log::error('Error deleting product image: ' . $e->getMessage());

            return response()->json(['message' => 'Error deleting product image : ' . $e->getMessage()], 500);
        }
    }
}
