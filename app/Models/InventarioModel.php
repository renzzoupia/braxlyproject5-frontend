<?php
namespace App\Models;
use CodeIgniter\Model;
class InventarioModel extends Model{
    protected $table = 'inventario';
    protected $primaryKey = 'inv_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'prod_id',
        'prov_id',
        'inv_tipo_movimiento',
        'inv_cantidad',
        'inv_fecha_ing',
        'inv_fecha_vencimiento',
        'sucu_id',
        'inv_estado'
    ];
    //Como es una tabla relacionada con llaves foraneas vamos a crear
    //las relaciones en el modelo

    public function getInventario(){
        return $this -> db -> table('inventario i')
        -> where('i.inv_estado', 1)
        -> join('proveedores pv', 'i.prov_id = pv.prov_id')
        -> join('productos pd', 'i.prod_id = pd.prod_id')
        -> join('sucursal sc', 'i.sucu_id = sc.sucu_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('inventario i')
        -> where('i.inv_id', $id)
        //-> where('i.inv_estado', 1)
        -> join('proveedores pv', 'i.prov_id = pv.prov_id')
        -> join('productos pd', 'i.prod_id = pd.prod_id')
        -> join('sucursal sc', 'i.sucu_id = sc.sucu_id')
        -> get() -> getResultArray();
    }

    public function getProveedores(){
        return $this -> db -> table('proveedores pv')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }

    public function getProductos(){
        return $this -> db -> table('productos pd')
        //-> where('cc.estado', 1)
        -> get() -> getResultArray();
    }

    public function getSucursal(){
        return $this -> db -> table('sucursal sc')
        //-> where('nc.estado', 1)
        -> get() -> getResultArray();
    }
}