<?php
namespace App\Models;
use CodeIgniter\Model;
class ProveedoresModel extends Model{
    protected $table = 'proveedores';
    protected $primaryKey = 'prov_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'prov_nombre',
        'prov_direccion',
        'prov_telefono',
        'prov_estado'
        
    ];

}