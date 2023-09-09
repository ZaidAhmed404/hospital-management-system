<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\prescription;
use App\Models\notes;
use App\Models\remarks;
use App\Models\attendbee;
use App\Models\attendcee;
class patient extends Model
{
    use HasFactory;

    public function prescription(){
        return $this->hasMany(prescription::class,'patientId');
    }

    public function notes(){
        return $this->hasMany(notes::class,'patientId');
    }
    
    public function remarks(){
        return $this->hasMany(remarks::class,'patientId');
    }

    public function attendbee(){
        return $this->hasMany(attendbee::class,'patientId');
    }

    public function attendcee(){
        return $this->hasMany(attendcee::class,'patientId');
    }
}
