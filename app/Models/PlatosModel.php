<?php
namespace App\Models;
use CodeIgniter\Model;
class PlatosModel extends Model{
    protected $table = 'platos';
    protected $primaryKey = 'pla_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'pla_comida',
        'pla_precio',
        'pla_descrip',
        'tico_id',
        'sucu_id',
        'pla_estado'
    ];

    public function getPlatos(){
        return $this -> db -> table('platos p')
        -> where('p.pla_estado', 1)
        -> join('sucursal s', 'p.sucu_id = s.sucu_id')
        -> join('tipo_comida tc', 'p.tico_id = tc.tico_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('platos p')
        -> where('p.pla_id', $id)
        //-> where('i.inv_estado', 1)
        -> join('sucursal s', 'p.sucu_id = s.sucu_id')
        -> join('tipo_comida tc', 'p.tico_id = tc.tico_id')
        -> get() -> getResultArray();
    }

    public function getSucursal(){        
        return $this -> db -> table('sucursal s')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }

    public function getTipoComida(){        
        return $this -> db -> table('tipo_comida tc')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }
   

    
}