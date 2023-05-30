<?php
namespace App\Models;
use CodeIgniter\Model;
class ClientesModel extends Model{
    protected $table = 'clientes';
    protected $primaryKey = 'cli_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'per_id',
        'cli_estado'
    ];
    //Como es una tabla relacionada con llaves foraneas vamos a crear
    //las relaciones en el modelo

    public function getClientes(){
        return $this -> db -> table('clientes c')
        -> where('c.cli_estado', 1)
        -> join('personas p', 'c.per_id = p.per_id')
        -> get() -> getResultArray();
    }

    public function getId($id){
        return $this -> db -> table('clientes c')
        -> where('c.cli_id', $id)
        //-> where('i.inv_estado', 1)
        -> join('personas p', 'c.per_id = p.per_id')
        -> get() -> getResultArray();
    
    }

    public function getPersonas(){
        return $this -> db -> table('personas p')
        //-> where('nc.estado', 1)
        -> get() -> getResultArray();
    }
}