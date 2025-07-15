<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gradesubject extends Model
{
    protected $fillable=[
        'grade_id',
        'academicyear_id',
        'name',
        'code',
    ];
    public function grade(){
        return $this->belongsTo(Grade::class);
    }
    public function academicyear(){
        return $this->belongsTo(Academicyear::class);
    }
}
