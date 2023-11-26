<?php

namespace App\Http\Resources\School;

use App\Http\Resources\School\SubmissionsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantsResource extends JsonResource
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
            'id'          => $this->id,
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'email'       => $this->email,
            'name'        => $this->name,
            'created_at'  => $this->created_at->diffForHumans(),
            'submissions' => ($this->submissions) ? SubmissionsResource::collection($this->submissions) : null,
        ];
    }
}
