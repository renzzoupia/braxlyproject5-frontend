<?php
namespace App\Models;
use CodeIgniter\Model;
class SucursalModel extends Model{
    protected $table = 'sucursal';
    protected $primaryKey = 'sucu_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'sucu_nombre',
        'sucu_apellidos',
        'sucu_direccion',
        'sucu_telefono',
        'sucu_departamento',
        'sucu_provincia',
        'sucu_estado',
        'empr_id'
    ];

    public function getSucursal(){
        return $this -> db -> table('sucursal s')
        -> where('s.sucu_estado', 1)
        -> join('empresa em', 's.empr_id = em.empr_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('sucursal s')
        -> where('s.sucu_id', $id)
        //-> where('i.inv_estado', 1)
        -> join('empresa em', 's.empr_id = em.empr_id')
        -> get() -> getResultArray();
    }

    public function getEmpresa(){        
        return $this -> db -> table('empresa em')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }

   

    
}