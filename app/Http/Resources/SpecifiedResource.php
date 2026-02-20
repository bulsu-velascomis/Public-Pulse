<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecifiedResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "section_header_id" => $this->section_header_id,
            "type" => $this->type,
            "questions" => $this->questions,
        ];
    }
}
