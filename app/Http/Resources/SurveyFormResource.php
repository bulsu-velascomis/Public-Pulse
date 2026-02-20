<?php

namespace App\Http\Resources;

use App\Http\Resources\ChargingResource;
use App\Http\Resources\QuestionnaireResource;
use App\Http\Resources\RecordResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyFormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'attachment' => $this->attachment ? url('storage/' . $this->attachment) : null,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'user' => new UserResource($this->whenLoaded('user')),
            'questionnaire' => new QuestionnaireResource($this->whenLoaded('questionnaire')),
            'chargings' => ChargingResource::collection($this->whenLoaded('chargings')),
            'record' => RecordResource::collection($this->whenLoaded('record')),
        ];
    }
}
