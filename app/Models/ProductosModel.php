<?php
namespace App\Models;
use CodeIgniter\Model;
class ProductosModel extends Model{
    protected $table = 'productos';
    protected $primaryKey = 'prod_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'prod_nombre',
        'prod_descripcion',
        'prod_precio',
        'tipr_id',
        'prod_estado',
        'prod_unidad'
    ];

    public function getProductos(){
        return $this -> db -> table('productos pr')
        -> where('pr.prod_estado', 1)
        -> join('tipo_producto tp', 'pr.tipr_id = tp.tipr_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('productos pr')
        -> where('pr.prod_id', $id)
        //-> where('i.inv_estado', 1)
        -> join('tipo_producto tp', 'pr.tipr_id = tp.tipr_id')
        -> get() -> getResultArray();
    }

    public function getTipoProducto(){        
        return $this -> db -> table('tipo_producto tp')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }
}