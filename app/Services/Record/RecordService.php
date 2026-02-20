<?php

namespace App\Services\Record;

use App\Models\Record;

class RecordService
{
    public function getRecords($status)
    {
        $record = Record::with([
            "surveyForm",
            "surveyForm.user",
            "surveyForm.questionnaire",
            "surveyForm.questionnaire.sectionHeader",
            "surveyForm.questionnaire.answer",
            "surveyForm.chargings",
        ])
            ->when($status === "inactive", function ($query) {
                $query->onlyTrashed();
            })
            ->useFilters()
            ->dynamicPaginate();

        return $record;
    }

    // public function createRecord($data)
    // {
    //     return Record::create($data);
    // }

    // public function updateRecord($id, $data)
    // {
    //     $record = Record::withTrashed()->find($id);

    //     if (! $record) {
    //         return null;
    //     }

    //     $record->update($data);

    //     return $record;
    // }

    // public function deleteRecord($id)
    // {
    //     $record = Record::withTrashed()->find($id);

    //     if (! $record) {
    //         return null;
    //     }

    //     $record->delete();

    //     return $record;
    // }
}
