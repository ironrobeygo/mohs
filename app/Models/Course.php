<?php

namespace App\Models;

use App\Models\User;
use App\Models\Module;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    CONST DRAFT = 'draft';
    CONST PENDING = 'pending';
    CONST ONLINE  = 'online';
    CONST OFFLINE = 'offline';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'instructor_id',
        'user_id',
        'updated_by',
        'status'
    ];

    protected $with = array('category', 'user');

    public function path(){
        return "/api/courses/{$this->id}";
    }

    public function setSlugAttribute($value){
        $this->attributes['slug'] = Str::of($value)->slug('-');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function isOnline(){
        return $this->status == self::ONLINE;
    }

    public function modules(){
        return $this->hasMany(Module::class);
    }

    public function addModule($data){
        return $this->modules()->create($data);
    }


}
