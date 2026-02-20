<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->data;
        $params = $data['message_params'] ?? [];

        return [
            'id'               => $this->id,
            'type'             => $this->type,
            'title'            => isset($data['title_key']) ? __($data['title_key'], $params) : null,
            'message'          => isset($data['message_key']) ? __($data['message_key'], $params) : null,
            'is_read'          => !is_null($this->read_at),
            'created_at_human' => $this->created_at->diffForHumans(),
            'created_at'       => $this->created_at->toIso8601String(),
            'extra'            => collect($data)->except(['title_key', 'message_key', 'message_params']),
        ];
    }
}
