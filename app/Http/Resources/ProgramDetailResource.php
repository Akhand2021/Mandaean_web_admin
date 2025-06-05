<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramDetailResource extends JsonResource
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
                'description'   => $this->ar_description,
                'image'         => ($this->image)?url('/').'/public/'.$this->image:null,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
                'created_at'    => date("Y-m-d H:i:s",strtotime($this->created_at)),
            ];
        }elseif ($request->lang == 'pe') {
            return [
                'id'            => $this->id,
                'title'         => $this->pe_title,
                'group'         => $this->pe_group,
                'description'   => $this->pe_description,
                'image'         => ($this->image)?url('/').'/public/'.$this->image:null,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
                'created_at'    => date("Y-m-d H:i:s",strtotime($this->created_at)),
            ];
        }else{
            return [
                'id'            => $this->id,
                'title'         => $this->title,
                'group'         => $this->group,
                'description'   => $this->description,
                'image'         => ($this->image)?url('/').'/public/'.$this->image:null,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
                'created_at'    => date("Y-m-d H:i:s",strtotime($this->created_at)),
            ];
        }
    }
}
