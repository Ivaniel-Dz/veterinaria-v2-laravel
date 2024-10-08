<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'celular', 'telefono', 'direccion'];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_propietario');
    }
}
