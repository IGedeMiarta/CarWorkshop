<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name','price'];
    protected $primaryKey = 'id_services';
    public function carService()
    {
        return $this->hasMany(CarService::class,'service_id','id_services');
    }
}
