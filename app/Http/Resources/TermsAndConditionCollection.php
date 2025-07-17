<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TermsAndConditionCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'content'   => strip_tags($this->content),
            'is_active' => $this->is_active ? true : false,
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
