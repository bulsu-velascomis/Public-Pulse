<?php

namespace App\Models;

use App\Filters\RecordFilter;
use App\Models\Report;
use App\Models\SurveyForm;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected string $default_filters = RecordFilter::class;

    protected $table = "records";

    protected $fillable = [
        'survey_form_id',
    ];

    public function surveyForm()
    {
        return $this->belongsTo(SurveyForm::class, "survey_form_id");
    }

    public function report()
    {
        return $this->hasMany(Report::class, "record_id");
    }
}
