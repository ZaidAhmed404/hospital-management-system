<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\vouchermedicine;
class voucher extends Model
{
    use HasFactory;

    public function medicines(){
        return $this->hasMany(vouchermedicine::class,'voucherId');
    }

}
