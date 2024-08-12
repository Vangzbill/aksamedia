<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    protected $table = 'divisi';

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'division_id', 'id');
    }
}
