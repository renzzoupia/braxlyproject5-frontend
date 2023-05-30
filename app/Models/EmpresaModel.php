<?php
namespace App\Models;
use CodeIgniter\Model;
class EmpresaModel extends Model{
    protected $table = 'empresa';
    protected $primaryKey = 'empr_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'empr_nombre',
        'empr_telefono',
        'empr_correo',
        'empr_estado'
    ];
}