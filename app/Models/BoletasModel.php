<?php
namespace App\Models;
use CodeIgniter\Model;
class BoletasModel extends Model{
    protected $table = 'boletas';
    protected $primaryKey = 'bol_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'tra_id',
        'bol_fech_emision',
        'bol_monto_total',
        'deco_info_compra',
        'depe_id',
        'bol_estado'
    ];
    //Como es una tabla relacionada con llaves foraneas vamos a crear
    //las relaciones en el modelo

    public function getBoletas(){
        return $this -> db -> table('boletas b')
        -> where('b.bol_estado', 1)
        -> join('trabajadores tr', 'b.tra_id = tr.tra_id')
        -> join('detalle_pedido dp', 'b.depe_id = dp.depe_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('boletas b')
        -> where('b.bol_id', $id)
        //-> where('i.inv_estado', 1)
        -> join('trabajadores tr', 'b.tra_id = tr.tra_id')
        -> join('detalle_pedido dp', 'b.depe_id = dp.depe_id')
        -> get() -> getResultArray();
    }

    public function getTrabajadores(){
        return $this -> db -> table('trabajadores tr')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }

    public function getDetallePedido(){
        return $this -> db -> table('detalle_pedido dp')
        //-> where('cc.estado', 1)
        -> get() -> getResultArray();
    }

}