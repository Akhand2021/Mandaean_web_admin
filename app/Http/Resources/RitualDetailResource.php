<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RitualDetailResource extends JsonResource
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
                'id'            => $this->id,
                'title'         => $this->ar_title,
                'description'   => $this->ar_description,
                'url'           => ($this->docs)?url('/').'/'.$this->docs:null,
            ];
        }elseif ($request->lang == 'pe') {
            return [
                'id'            => $this->id,
                'title'         => $this->pe_title,
                'description'   => $this->pe_description,
                'url'           => ($this->docs)?url('/').'/'.$this->docs:null,
            ];
        }else{
            return [
                'id'            => $this->id,
                'title'         => $this->title,
                'description'   => $this->description,
                'url'           => ($this->docs)?url('/').'/'.$this->docs:null,
            ];
        }
    }
}
