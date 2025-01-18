<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password', 'role', 'name', 'dpi', 'group_id', 'pos', 'estado', 'territorio'];
    protected $returnType = 'array';
    protected $useTimestamps = true;
}
