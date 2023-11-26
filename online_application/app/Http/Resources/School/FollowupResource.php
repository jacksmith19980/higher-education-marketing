<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowupResource extends JsonResource
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
            'name'      => $this->name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'type'      => $this->type,
            'status'    => $this->status,
            'date'      => $this->created_at->diffForHumans(),
        ];
    }
}
