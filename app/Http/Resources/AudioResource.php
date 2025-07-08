<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AudioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => strip_tags($this->description),
            'audio_url' => $this->file_path ? asset($this->file_path) : null,
            'user' => $this->whenLoaded('user'),
            'post' => $this->whenLoaded('post'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
