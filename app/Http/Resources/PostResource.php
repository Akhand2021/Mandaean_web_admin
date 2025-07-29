<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'image_url' => $this->image_url ? url($this->image_url) : null,
            'user' => new UserResource($this->whenLoaded('user')),
            'likes_count' => $this->whenCounted('likes'),
            'comments_count' => $this->whenCounted('comments'),
            'shares_count' => $this->whenCounted('shares'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
