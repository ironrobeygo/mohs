<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    CONST DRAFT = 'draft';
    CONST ACTIVE  = 'active';
    CONST INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'slug',
        'content',
        'module_id',
        'user_id',
        'updated_by',
        'status'
    ];

    public function path(){
        return "/api/courses/{$this->module->course_id}/modules/{$this->module->id}/units/{$this->id}";
    }

    public function setSlugAttribute($value){
        $this->attributes['slug'] = Str::of($value)->slug('-');
    }

    public function module(){
        return $this->belongsTo(Module::class);
    }
    
    

}
