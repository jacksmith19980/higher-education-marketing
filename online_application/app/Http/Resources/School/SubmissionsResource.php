<?php

namespace App\Http\Resources\School;

use App\Http\Resources\School\ApplicationsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionsResource extends JsonResource
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
            'id'            => $this->id,
            'status'        => $this->status,
            'created_at'    => $this->created_at->diffForHumans(),
            'updated_at'    => $this->updated_at->diffForHumans(),
            'application'   => new ApplicationsResource($this->application),
        ];
    }
}
