<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'media_url' => $this->getMediaUrl(),
            'media_type' => $this->media_type,
            'created_at' => $this->created_at,
            'expires_at' => $this->expires_at,
            'views_count' => $this->viewsCount(),
            'viewed_by_me' => $this->viewedBy(auth()->user()),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }

    /**
     * Get full media URL
     */
    private function getMediaUrl()
    {
        // If URL is already full (starts with http), return as is
        if (str_starts_with($this->media_url, 'http')) {
            return $this->media_url;
        }

        // If it's a storage path, create full URL
        if (str_starts_with($this->media_url, '/storage/')) {
            return url($this->media_url);
        }

        // If it's just a path, prepend storage URL
        return asset('storage/' . $this->media_url);
    }
}
