<?php
namespace App\Models;
use CodeIgniter\Model;
class ReclamosModel extends Model{
    protected $table = 'reclamos';
    protected $primaryKey = 'recl_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'recl_tipo_reclamo',
        'recl_descrip',
        'sucu_id',
        'recl_estado',
    ];

    public function getReclamos(){
        return $this -> db -> table('reclamos r')
        -> where('r.recl_estado', 1)
        -> join('sucursal sc', 'r.sucu_id = sc.sucu_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('reclamos r')
        -> where('r.recl_id', $id)
        //-> where('i.inv_estado', 1)
        -> join('sucursal sc', 'r.sucu_id = sc.sucu_id')
        -> get() -> getResultArray();
    }

    public function getSucursal(){        
        return $this -> db -> table('sucursal sc')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }
}