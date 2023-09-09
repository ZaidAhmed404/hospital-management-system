<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prescription extends Model
{
    use HasFactory;
    public function patient(){
        return $this->belongsTo(patient::class,'patientId');
    }

    public function medicine(){
        return $this->belongsTo(storemedicines::class,'medicineId');
    }
}
