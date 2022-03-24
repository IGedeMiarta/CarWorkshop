<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;
    protected $fillable = ['name','phone','user_id'];
    protected $primaryKey = 'id_mechanics';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function CarRepair(){
        return $this->hasMany(CarRepair::class,'mechanic_id','id_mechanics');
    }

}
