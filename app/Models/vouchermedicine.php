<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vouchermedicine extends Model
{
    use HasFactory;

    public function medicinename(){
        return $this->belongsTo(storemedicines::class,'medicineId');
    }
}
