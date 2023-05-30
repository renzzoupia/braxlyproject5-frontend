<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UsuariosModel;
class Usuarios extends Controller{

    public function index(){
        $model = new UsuariosModel();
        $registro = $model /*-> where('estado', 1)*/ -> findAll();
        //var_dump($registro); die;
        if(count($registro) == 0){
            $respuesta = array(
                "error" => true,
                "mensaje" => "No hay elemento"
            );
            $data = json_encode($respuesta);
            //var_dump($respuesta); die;
            
        }else{
            $data = json_encode($registro);
        }
        return $data;

    }

    public function create(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $datos = array(
            "usu_nombres" => $request -> getVar("usu_nombres"),
            "usu_apellidos" => $request -> getVar("usu_apellidos"),
            "tiad_id " => $request -> getVar("tiad_id"),
            "empr_id " => $request -> getVar("empr_id"),
            "sucu_id " => $request -> getVar("sucu_id "),
        );
        if(!empty($datos)){
            $validation -> setRules([
                'usu_nombres' => 'required|string|max_length[255]',
                'usu_apellidos' => 'required|string|max_length[255]',
                'tiad_id' => 'required|int',
                'empr_id' => 'required|int',
                'sucu_id' => 'required|int',
            ]);
            $validation -> withRequest($this -> request) -> run();
            if($validation -> getErrors()){
                $errors = $validation ->getErrors();
                $data = array(
                    "Status" => 404,
                    "Detalle" => $errors
                );
                return json_encode($data, true);
            }else{
                $usu_usuario = crypt($datos["usu_nombres"].$datos["usu_apellidos"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
	     		$usu_clave = crypt($datos["usu_apellidos"].$datos["usu_nombres"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
                $datos = array(
                    "usu_nombres" => $datos["usu_nombres"],
                    "usu_apellidos" => $datos["usu_apellidos"],
                    "usu_usuario" => str_replace('$','a',$usu_usuario),
                    "usu_clave" => str_replace('$','o',$usu_clave)
                );
                $model = new UsuariosModel();
                $registro = $model -> insert($datos);
                $data = array(
                    "Status" => 200,
                    "Detalle" => "Registro OK, guarde sus credenciales",
                    "credenciales" => array(
                        "usu_usuario" => str_replace('$','a',$usu_usuario),
                        "usu_clave" => str_replace('$','o',$usu_clave)
                    )
                );
                return json_encode($data, true);
            }
        }else{
            $data = array(
                "Status" => 400,
                "Detalle" => "Registro con errores"
            );
            return json_encode($data, true);
        }
    }

}
