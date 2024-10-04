<?php

namespace App\Models;

use CodeIgniter\Model;

class HorasExtrasModel extends Model
{
    protected $table = 'extra_hours';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'pos_id',
        'date',
        'time',
        'comment',
        'point_of_sale',
        'entry_or_exit',
        'horas',
        'estado',
        'created_at',
        'updated_at',
        'horasalida'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
}
