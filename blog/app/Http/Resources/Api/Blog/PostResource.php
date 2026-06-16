<?php

namespace App\Http\Resources\Api\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'date_published' => $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null,
            'category_id'    => $this->category_id,
            'category_title' => $this->category?->title,
        ];
    }
}
