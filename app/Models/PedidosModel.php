<?php
namespace App\Models;
use CodeIgniter\Model;
class PedidosModel extends Model{
    protected $table = 'pedidos';
    protected $primaryKey = 'ped_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'ped_num_pedido',
        'ped_tipo_compra',
        'ped_estado_pedido',
        'ped_detalles',
        'pla_id',
        'cli_id',
        'ped_estado'
    ];

    public function getPedidos(){
        return $this -> db -> table('pedidos p')
        -> where('p.ped_estado', 1)
        -> join('platos pt', 'p.pla_id = pt.pla_id')
        -> join('clientes cl', 'p.cli_id = cl.cli_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('pedidos p')
        -> where('p.ped_id ', $id)
        //-> where('i.inv_estado', 1)
        -> where('p.ped_estado', 1)
        -> join('platos pt', 'p.pla_id = pt.pla_id')
        -> join('clientes cl', 'p.cli_id = cl.cli_id')
        -> get() -> getResultArray();
    }

    public function getPlatos(){        
        return $this -> db -> table('platos pt')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }

    public function getClientes(){        
        return $this -> db -> table('clientes cl')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }
   

    
}