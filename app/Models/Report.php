<?php

namespace App\Models;

use App\Filters\ReportFilter;
use App\Models\Record;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, Filterable;

    protected string $default_filters = ReportFilter::class;

    protected $fillable = [
        'record_id',
    ];

    public function record()
    {
        return $this->belongsTo(Record::class, "record_id");
    }
}
