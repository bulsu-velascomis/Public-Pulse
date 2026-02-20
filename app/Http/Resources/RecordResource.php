<?php

namespace App\Http\Resources;

use App\Http\Resources\SurveyFormResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "survey_form_id" => $this->survey_form_id,

            "survey_form" => new SurveyFormResource($this->whenLoaded("surveyForm")),
            'reports' => ReportResource::collection($this->whenLoaded('reports')),
        ];
    }
}
