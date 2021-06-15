<?php

namespace App\Models;

use App\Models\Quiz;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    CONST DRAFT = 'draft';
    CONST ACTIVE  = 'active';
    CONST APPROVED = 'approve';
    CONST REJECTED = 'rejected';
    CONST INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'slug',
        'module_id',
        'user_id',
        'updated_by',
        'status'
    ];

    public function path(){
        return "/api/courses/{$this->module->course_id}/modules/{$this->module->id}/quizzes/{$this->id}";
    }

    public function setSlugAttribute($value){
        $this->attributes['slug'] = Str::of($value)->slug('-');
    }

    public function module(){
        return $this->belongsTo(Module::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function addQuestion($data){
        return $this->questions()->create($data);
    }
}
