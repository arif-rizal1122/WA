<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'status' => new StatusResource($this->whenLoaded('status')),
            'id' => $this->id,
            'isi_comment' => $this->isi_comment,
            'picture' => $this->picture,
            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}
