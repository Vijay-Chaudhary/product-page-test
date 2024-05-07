<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $discount = new ProductDiscountResource($this->whenLoaded('discount'));

        if (!empty($discount) && is_array($discount)) {
            if ($discount['type'] == 'percent') {
                $finalPrice = ['discounted' => round($this->price - ($this->price * $discount['discount'] / 100),2), 'full' => $this->price];
            } else {
                $finalPrice = ['discounted' => round($this->price - $discount['discount'], 2), 'full' => $this->price];
            }
        } else {
            $finalPrice = $this->price;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $finalPrice,
            'active' => $this->active,
            'slug' => $this->slug,
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'discount' => $discount,
        ];
    }
}
