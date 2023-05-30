<?php
namespace App\Models;
use CodeIgniter\Model;
class RegistrosModel extends Model{
    protected $table = 'registros';
    protected $primaryKey = 'reg_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'reg_nombres',
        'reg_apellidos',
        'reg_email',
        'reg_clientes_id',
        'reg_llave_secreta',
        'reg_estado'
    ];
}