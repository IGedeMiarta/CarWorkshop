<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRepair extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_repairs';
    protected $fillable = ['owner_id','mechanic_id','status_id','note','car_entry'];

    public function owner()
    {
        return $this->belongsTo(CarOwner::class,'owner_id','id_car_owners');
    }
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class,'mechanic_id','id_mechanics');
    }
    public function status()
    {
        return $this->belongsTo(Status::class,'status_id','id_status');
    }
    public function carService()
    {
        return $this->hasMany(CarService::class,'repair_id','id_repairs');
    }
}
