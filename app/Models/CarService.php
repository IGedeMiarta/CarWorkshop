<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarService extends Model
{
    use HasFactory;
    protected $fillable =['repair_id','service_id'];
    
    public function carRepair()
    {
        return $this->belongsTo(CarRepair::class,'repair_id','id_repair');
    }
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id_services');
    }
}
