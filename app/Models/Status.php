<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $fillable = ['status'];
    protected $primaryKey = 'id_status';
    protected $table = 'statuses';
    public function CarRepair()
    {
        return $this->hasMany(CarRepair::class,'status_id','id_status');
    }
}
