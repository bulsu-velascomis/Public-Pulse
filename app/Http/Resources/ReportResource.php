<?php

namespace App\Http\Resources;

use App\Http\Resources\RecordResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "record_id" => $this->record_id,
           // 'answer_value' => $this->answer_value,
            
            "record" => new RecordResource($this->whenLoaded("record")),
           // 'questionnaire' => new QuestionnaireResource($this->whenLoaded('questionnaire')),
        ];
    }
}
