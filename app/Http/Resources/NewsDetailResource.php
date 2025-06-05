<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsDetailResource extends JsonResource
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
                'group'         => $this->ar_group,
                'country'       => $this->country,
                'date'          => $this->date,
                'description'   => $this->ar_description,
                'image'         => url('/').'/public/'.$this->image,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
                'created_at'    => date("Y-m-d H:i:s",strtotime($this->created_at)),
            ];
        }elseif ($request->lang == 'pe') {
            return [
                'id'            => $this->id,
                'title'         => $this->pe_title,
                'group'         => $this->pe_group,
                'country'       => $this->country,
                'date'          => $this->date,
                'description'   => $this->pe_description,
                'image'         => url('/').'/public/'.$this->image,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
                'created_at'    => date("Y-m-d H:i:s",strtotime($this->created_at)),
            ];
        }else{
            return [
                'id'            => $this->id,
                'title'         => $this->title,
                'group'         => $this->group,
                'country'       => $this->country,
                'date'          => $this->date,
                'description'   => $this->description,
                'image'         => url('/').'/public/'.$this->image,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
                'created_at'    => date("Y-m-d H:i:s",strtotime($this->created_at)),
            ];
        }
    }
}
