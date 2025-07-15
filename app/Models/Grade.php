<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable=['name'];
    public function gradeSubjects(){
        return $this->hasMany(Gradesubject::class);
    }
}
