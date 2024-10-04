<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table = 'puntosventa';
    protected $primaryKey = 'idPos';
    protected $allowedFields = ['name', 'qr', 'address', 'mobile'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
}
