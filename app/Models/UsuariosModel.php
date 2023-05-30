<?php
namespace App\Models;
use CodeIgniter\Model;
class UsuariosModel extends Model{
    protected $table = 'usuarios';
    protected $primaryKey = 'usu_id ';
    protected $returnType = 'array';
    protected $allowedFields = [
        'usu_usuario',
        'usu_clave',
        'tiad_id ',
        'empr_id ',
        'sucu_id ',
        'usu_estado',
        'usu_nombres',
        'usu_apellidos'
    ];
}