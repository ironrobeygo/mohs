<?php

namespace App\Models;

use App\Models\Quiz;
use App\Models\Unit;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    CONST DRAFT = 'draft';
    CONST ACTIVE  = 'active';
    CONST INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'user_id',
        'updated_by',
        'status'
    ];

    public function path(){
        return "/api/courses/{$this->course->id}/modules/{$this->id}";
    }

    public function setSlugAttribute($value){
        $this->attributes['slug'] = Str::of($value)->slug('-');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function units(){
        return $this->hasMany(Unit::class);
    }

    public function quizzes(){
        return $this->hasMany(Quiz::class);
    }

    public function addUnit($data){
        return $this->units()->create($data);
    }

    public function addQuiz($data){
        return $this->quizzes()->create($data);
    }


}
