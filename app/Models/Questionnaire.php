<?php

namespace App\Models;

use App\Models\User;
use App\Models\Answer;
use App\Models\SurveyForm;
use App\Models\SectionHeader;
use App\Filters\QuestionnaireFilter;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questionnaire extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected string $default_filters = QuestionnaireFilter::class;

    protected $table = "questionnaires";

    // protected $fillable = [
    //     "section_header_id",
    //     "answer_id",
    //     "questions",
    //     "display_order",
    // ];

    protected $fillable = [
        "questions",
        "description",
        "section_header_id",
        "type",
        //'display_order',
    ];

    protected $casts = [
        "questions" => "array",
    ];

    public function sectionHeader()
    {
        return $this->belongsTo(SectionHeader::class, "section_header_id");
    }

    public function surveyForm()
    {
        return $this->hasMany(SurveyForm::class, "questionnaire_id");
    }

    public function answer()
    {
        return $this->hasMany(Answer::class, "questionnaire_id");
    }
}
