<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryCollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->first()->user),
            'stories' => StoryResource::collection($this),
            'total_stories' => $this->count(),
            'has_unviewed' => $this->contains(function ($story) {
                return !$story->viewedBy(auth()->user());
            })
        ];
    }
}
