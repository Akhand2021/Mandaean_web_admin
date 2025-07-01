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
            'user' => new UserResource($this->whenLoaded('user')),
            'content' => $this->content,
            'image_url' => $this->image_url ? asset($this->image_url) : null,
            'likes_count' => $this->likes->count(),
            'comments_count' => $this->comments->count(),
            'shares_count' => $this->shares->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'shares' => ShareResource::collection($this->whenLoaded('shares')),
        ];
    }
}
