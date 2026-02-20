<?php

namespace App\Models;

use App\Filters\SectionFilter;
use App\Models\Questionnaire;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionHeader extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected string $default_filters = SectionFilter::class;

    protected $table = "section_headers";

    protected $fillable = ["name"];

    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class, "section_header_id");
    }
}
