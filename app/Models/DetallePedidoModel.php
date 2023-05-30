<?php
namespace App\Models;
use CodeIgniter\Model;
class DetallePedidoModel extends Model{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'depe_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'ped_id',
        'depe_estado',
        'depe_fecha',
        'tipa_id',
        'depe_num_mesa',
        'deco_info_compra',
        'tra_id',
        'ticon_id',
        'depe_estado'
    ];

    public function getDetallePedido(){
        return $this -> db -> table('detalle_pedido dp')
        -> where('dp.depe_estado', 1)
        -> join('pedidos pd', 'dp.ped_id = pd.ped_id')
        -> join('tipo_pago tp', 'dp.tipa_id = tp.tipa_id')
        -> join('trabajadores tb', 'dp.tra_id = tb.tra_id')
        -> join('tipo_consumo tc', 'dp.ticon_id = tc.ticon_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('detalle_pedido dp')
        -> where('dp.depe_id', $id)
        -> where('dp.depe_estado', 1)
        -> join('pedidos pd', 'dp.ped_id = pd.ped_id')
        -> join('tipo_pago tp', 'dp.tipa_id = tp.tipa_id')
        -> join('trabajadores tb', 'dp.tra_id = tb.tra_id')
        -> join('tipo_consumo tc', 'dp.ticon_id = tc.ticon_id')
        -> get() -> getResultArray();
    }

    public function getPedidos(){        
        return $this -> db -> table('pedidos pd')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }

    public function getTipoPago(){        
        return $this -> db -> table('tipo_pago tp')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }
   
    public function getTrabajadores(){        
        return $this -> db -> table('trabajadores tb')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }
    
    public function getTipoConsumo(){        
        return $this -> db -> table('tipo_consumo tc')
        //-> where('pv.estado', 1)
        -> get() -> getResultArray();
    }
}