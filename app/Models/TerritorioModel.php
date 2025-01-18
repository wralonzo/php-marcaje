<?php

namespace App\Models;

use CodeIgniter\Model;

class TerritorioModel extends Model
{
    protected $table = 'territorio';
    protected $primaryKey = 'id_territorio';
    protected $allowedFields = ['nombre'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
}
