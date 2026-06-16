<?php

namespace App\Http\Resources\Api\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'excerpt'        => $this->excerpt,
            'content_raw'    => $this->content_raw,
            'is_published'   => (bool) $this->is_published,
            'date_published' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'category'       => new CategoryResource($this->whenLoaded('category')),
            'author'         => $this->whenLoaded('user', function () {
                return [
                    'id'   => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}
