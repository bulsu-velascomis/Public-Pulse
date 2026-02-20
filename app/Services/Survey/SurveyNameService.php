<?php

namespace App\Services\Survey;

use App\Models\SurveyName;

class SurveyNameService
{
    public function getSurveyName($status)
    {
        $surveyName = SurveyName::when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })
        ->useFilters()
        ->dynamicPaginate();

        return $surveyName;
    }

    public function createSurveyName($data)
    {
        return SurveyName::create($data);
    }

    public function updateSurveyName($id, $data)
    {
        $surveyName = SurveyName::find($id);

        if (!$surveyName) {
            return null;
        }

        $surveyName->update($data);
        return $surveyName;
    }

    public function deleteOrRestoreSurveyName($id)
    {
        $surveyName = SurveyName::withTrashed()->find($id);

        if (!$surveyName) {
            return null;
        }
        
        return $surveyName;
    }
}