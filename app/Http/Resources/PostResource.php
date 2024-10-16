<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name_user' => $this->user->name,
            'title_post' => $this->title,
            'body_post' => $this->body,
            'cover_image_post' => $this->cover_image,
            'pinned_post' => $this->pinned,
            'tags' => $this->tags->pluck('name') ,
        ];
    }
}
