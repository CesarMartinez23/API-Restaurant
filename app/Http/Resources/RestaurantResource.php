<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image_path' => url("Restaurants/" . $this->image_path),
            'description' => $this->description,
            'address' => $this->address,
            'phone' => $this->phone,
            'category' => $this->category->name,
            'dishes' => DishResource::collection($this->dishes),
        ];
    }
}