<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LiftResource extends JsonResource
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
            'weight' => $this->weight,
            'reps' => $this->reps,
            'type' => $this->type,
            'date' => $this->date,
            'user' => $this->user,
            'id' => $this->id,
            'human_date' => date("F jS, Y", strtotime($this->date)),
        ];
    }
}
