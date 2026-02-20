<?php

namespace App\Models;

use App\Models\User;
use App\Models\SurveyForm;
use App\Filters\ChargingFilter;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Charging extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected string $default_filters = ChargingFilter::class;

    protected $table = "chargings";

    protected $fillable = ["name", "code"];

    protected $casts = [
        "data" => "array",
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function surveyForms()
    {
        return $this->belongsToMany(
            SurveyForm::class,
            "charging_survey_form",
            "charging_id",
            "survey_form_id"
        );
    }
}
