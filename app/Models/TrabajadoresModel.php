<?php
namespace App\Models;
use CodeIgniter\Model;
class TrabajadoresModel extends Model{
    protected $table = 'trabajadores';
    protected $primaryKey = 'tra_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'per_id',
        'tra_sueldo',
        'titra_id',
        'sucu_id',
        'tra_estado'
    ];
    //Como es una tabla relacionada con llaves foraneas vamos a crear
    //las relaciones en el modelo

    public function getTrabajadores(){
        return $this -> db -> table('trabajadores t')
        -> where('t.tra_estado', 1)
        -> join('tipo_trabajador tt', 't.titra_id = tt.titra_id')
        -> join('sucursal s', 't.sucu_id = s.sucu_id')
        -> join('personas p', 't.per_id = p.per_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('trabajadores t')
        -> where('t.tra_id', $id)
        -> where('t.tra_estado', 1)
        -> join('tipo_trabajador tt', 't.titra_id = tt.titra_id')
        -> join('sucursal s', 't.sucu_id = s.sucu_id')
        -> join('personas p', 't.per_id = p.per_id')
        -> get() -> getResultArray();
    }

    public function getTipoTrabajador(){
        return $this -> db -> table('tipo_trabajador tt')
        //-> where('tt.tra_estado', 1)
        -> get() -> getResultArray();
    }

    public function getSucursal(){
        return $this -> db -> table('sucursal s')
        //-> where('s.tra_estado', 1)
        -> get() -> getResultArray();
    }

    public function getPersonas(){
        return $this -> db -> table('personas p')
        //-> where('s.tra_estado', 1)
        -> get() -> getResultArray();
    }
}