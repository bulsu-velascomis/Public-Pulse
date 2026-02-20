<?php

namespace App\Models;

use App\Filters\SurveyFormFilter;
use App\Models\Charging;
use App\Models\Questionnaire;
use App\Models\Record;
use App\Models\User;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyForm extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected string $default_filters = SurveyFormFilter::class;

    protected $table = "survey_forms";

    protected $fillable = [
        "user_id",
        "questionnaire_id",
        "name",
        "description",
        "status",
        "attachment",
        "date_start",
        "date_end",
    ];

    public function chargings()
    {
        return $this->belongsToMany(
            Charging::class,
            "charging_survey_form",
            "survey_form_id",
            "charging_id"
        )->withTrashed();
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, "questionnaire_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function records()
    {
        return $this->hasMany(Record::class, "survey_form_id");
    }
}
