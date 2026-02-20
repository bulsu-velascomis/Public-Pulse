<?php

namespace App\Models;

use App\Models\User;
use App\Filters\RoleFilter;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Role extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected string $default_filters = RoleFilter::class;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'access_permission',
    ];

    public function getAccessPermissionAttribute($value) {
        return explode(",", $value);
    }

    public function setAccessPermissionAttribute($value) {
        $this->attributes['access_permission'] = is_array($value) 
        ? implode(",", $value) 
        : $value;
        
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->access_permission);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
