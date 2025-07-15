<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Academicyear extends Model
{
    protected $fillable=[
        'name','start_date','end_date','active'
    ];
    protected $casts=[
        'active' => 'boolean',
    ];
    protected $table = 'academicyears'; // ✅ if your table name has no underscore

    public function gradeSubjects(){
        return $this->hasMany(Gradesubject::class);
    }
}
