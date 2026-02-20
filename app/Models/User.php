<?php

namespace App\Models;

use App\Filters\UserFilter;
use App\Models\Charging;
use App\Models\Role;
use App\Models\SurveyForm;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Filterable, SoftDeletes;

    protected string $default_filters = UserFilter::class;  
    
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'username',
        'password',
        'role_id',
        'charging_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function charging()
    {
        return $this->belongsTo(Charging::class);
    }

    public function surveyForms()
    {
        return $this->hasMany(SurveyForm::class);
    }
}
