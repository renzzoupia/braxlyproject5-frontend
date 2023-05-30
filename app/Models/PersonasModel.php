<?php
namespace App\Models;
use CodeIgniter\Model;
class PersonasModel extends Model{
    protected $table = 'personas';
    protected $primaryKey = 'per_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'per_nombres',
        'per_apellidos',
        'per_telefono',
        'per_dni',
        'per_correo',
        'per_estado'
    ];
}