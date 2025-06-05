<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrayerDetailResource extends JsonResource
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
                'subtitle'      => $this->ar_subtitle,
                'description'   => $this->ar_description,
                // 'other_info'    => $this->ar_other_info,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
            ];
        }elseif ($request->lang == 'pe') {
            return [
                'id'            => $this->id,
                'title'         => $this->pe_title,
                'subtitle'      => $this->pe_subtitle,
                'description'   => $this->pe_description,
                // 'other_info'    => $this->pe_other_info,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
            ];
        }else{
            return [
                'id'            => $this->id,
                'title'         => $this->title,
                'subtitle'      => $this->subtitle,
                'description'   => $this->description,
                // 'other_info'    => $this->other_info,
                'url'           => ($this->docs)?url('/').'/public/'.$this->docs:null,
            ];
        }
    }
}
