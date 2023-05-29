<?php
namespace App\Controllers;
use CodeIgniter\Controller;
class Saludo extends Controller{
    public function index(){
        echo  "Hola a todos";
    }
    
    public function comentarios(){
        $comentarios="Quiero decir muchas cosas";
        echo json_encode($comentarios);
        /*EL metodo json_encode
        permite transformar las variables 
        en el formato json, idea para web service con*/
    }
    public function mensajes($id){

        if(!is_numeric($id)){
            $respuesta = array(
                'error' => true,
                'mensaje' => 'Debe ser númerico'
            );

            echo json_encode($respuesta);
            return;
        }

        $mensajes = array(
            array(
                'id' => 1,
                'mensaje' => 'Hola 1'
            ),
            array(
                'id' => 2,
                'mensaje' => 'Hola 2'
            ),
            array(
                'id' => 3,
                'mensaje' => 'Hola 3'
            )
        );

        if($id >= count($mensajes) OR $id < 0){
            $respuesta = array(
                'error' => true,
                'mensaje' => 'El id no existe'
            );
            echo json_encode($respuesta);
            return;
        }
        echo json_encode($mensajes[$id]);
    }
}
