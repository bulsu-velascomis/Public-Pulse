<?php

namespace App\Models;

use App\Models\User;
use App\Filters\AnswerFilter;
use App\Models\Questionnaire;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    protected string $default_filters = AnswerFilter::class;

    protected $table = "answers";

    //    protected $fillable = ["options"];

    protected $fillable = [
        "questionnaire_id",
        "question_no",
        "answer",
    ];

    protected $casts = [
        "options" => "array",
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }
}
