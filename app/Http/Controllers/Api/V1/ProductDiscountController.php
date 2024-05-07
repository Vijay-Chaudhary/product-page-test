<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProductDiscountRequest;
use App\Http\Requests\UpdateProductDiscountRequest;

use App\Http\Resources\V1\ProductDiscountCollection;
use App\Http\Resources\V1\ProductDiscountResource;

use App\Filters\V1\ProductDiscountsFilter;
use App\Models\ProductDiscount;

class ProductDiscountController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $productDiscountsFilter = new ProductDiscountsFilter();

            $queryItems = $productDiscountsFilter->transform($request); // [['column', 'operator', 'value']]

            $includeProduct = $request->query('product', true);

            $productDiscounts = ProductDiscount::where($queryItems);

            if ($includeProduct == 'true') {
                $productDiscounts->with('product');
            }

            if( ! $productDiscounts->count() )  return response()->json(['message' => 'No product discounts found'], 404);

            return new ProductDiscountCollection($productDiscounts->paginate()->appends($request->query()));

        } catch (\Exception $e) {

            \Log::error('Error fetching product discounts: ' . $e->getMessage());

            return response()->json(['message' => 'Error fetching product discounts: '. $e->getMessage() ], 500);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductDiscount  $productDiscount
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDiscount $productDiscount)
    {
        try {

            $includeProduct = request()->query('product', true);

            if($includeProduct == 'true') {
                $productDiscount->loadMissing('product');
            }

            return new ProductDiscountResource($productDiscount);

        } catch (\Exception $e) {

            \Log::error('Error showing product discount: ' . $e->getMessage());

            return response()->json(['message' => 'Error showing product discount: '. $e->getMessage() ], 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductDiscount  $productDiscount
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDiscount $productDiscount)
    {

        try {

            $productDiscount->delete();

            return response()->json(['message' => 'Product discount deleted successfully', 'status' => 200], 200);

        } catch (\Exception $e) {

            \Log::error('Error deleting product discount: ' . $e->getMessage());

            return response()->json(['message' => 'Error deleting product discount: '. $e->getMessage() ], 500);
        }
    }
}
