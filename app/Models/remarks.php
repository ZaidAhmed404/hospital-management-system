<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\patient;
class remarks extends Model
{
    use HasFactory;

    public function patient(){
        return $this->belongsTo(patient::class,'patientId');
    }
}
