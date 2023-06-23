<?php

namespace App\Http\Resources;

use App\Http\Resources\ImagesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HewansResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'nama_hewan' => $this->nama_hewan,
            'description' => $this->description,
            'jenis_kelamin' => $this->jenis_kelamin,
            'images' => ImagesResource::collection($this->getMedia('images')),
            'umur' => $this->umur,
            'berat' => $this->berat,
            'status_vaksin' => $this->status_vaksin,
            'status' => $this->status,
            'type_id' => $this->type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}