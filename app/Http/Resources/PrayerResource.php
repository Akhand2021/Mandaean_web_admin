<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        if ($request->lang == 'ar') {
            return [
                'id'    => $this->id,
                'title' => $this->ar_title,
                'subtitle' => $this->ar_subtitle,
                'description' => strip_tags($this->ar_description),
                'url'      => ($this->docs) ? url('/') . '/public/' . $this->docs : null,
            ];
        } elseif ($request->lang == 'pe') {
            return [
                'id'    => $this->id,
                'title' => $this->pe_title,
                'subtitle' => $this->pe_subtitle,
                'description' => strip_tags($this->pe_description),
                'url'      => ($this->docs) ? url('/') . '/public/' . $this->docs : null,
            ];
        } else {
            return [
                'id'    => $this->id,
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'description' => strip_tags($this->description),
                'url'      => ($this->docs) ? url('/') . '/public/' . $this->docs : null,
            ];
        }
    }
}
