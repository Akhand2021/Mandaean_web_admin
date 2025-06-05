<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RitualResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        if($request->lang == 'ar'){
            return [
                'id'    => $this->id,
                'title' => $this->ar_title,
            ];
        }elseif ($request->lang == 'pe') {
            return [
                'id'    => $this->id,
                'title' => $this->pe_title,
            ];
        }else{
            return [
                'id'    => $this->id,
                'title' => $this->title,
            ];
        }
    }
}
