<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'option',
        'user_id',
        'updated_by',
    ];

    public function path(){
        return "/api/options/{$this->id}";
    } 

    public function questions(){
        return $this->belongsToMany(Question::class);
    }

    public static function scopeTruefalseOptions($query){

        return $query->where('option', 'true')->orWhere('option', 'false')->get();

    }
}
