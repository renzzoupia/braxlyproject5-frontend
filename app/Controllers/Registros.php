<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\RegistrosModel;
class Registros extends Controller{

    public function index(){
        $model = new RegistrosModel();
        $registro = $model -> where('reg_estado', 1) -> findAll();
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
            "reg_nombres" => $request -> getVar("reg_nombres"),
            "reg_apellidos" => $request -> getVar("reg_apellidos"),
            "reg_email" => $request -> getVar("reg_email")
        );
        if(!empty($datos)){
            $validation -> setRules([
                'reg_nombres' => 'required|string|max_length[255]',
                'reg_apellidos' => 'required|string|max_length[255]',
                'reg_email' => 'required|string|max_length[255]'
            ]);
            $validation -> withRequest($this -> request) -> run();
            if($validation -> getErrors()){
                $errors = $validation ->getErrors();
                $data = array(
                    "Status" => 404,
                    "Detalle"=>$errors
                );
                return json_encode($data, true);
            }else{
                $reg_clientes_id = crypt($datos["reg_nombres"].$datos["reg_apellidos"].$datos["reg_email"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
	     		$reg_llave_secreta = crypt($datos["reg_email"].$datos["reg_apellidos"].$datos["reg_nombres"], '$2a$07$dfhdfrexfhgdfhdferttgsad$');
                $datos = array(
                    "reg_nombres" => $datos["reg_nombres"],
                    "reg_apellidos" => $datos["reg_apellidos"],
                    "reg_email" => $datos["reg_email"],
                    "reg_clientes_id" => str_replace('$','a',$reg_clientes_id),
                    "reg_llave_secreta" => str_replace('$','o',$reg_llave_secreta)
                );
                $model = new RegistrosModel();
                $registro = $model -> insert($datos);
                $data = array(
                    "Status" => 200,
                    "Detalle" => "Registro OK, guarde sus credenciales",
                    "credenciales" => array(
                        "reg_clientes_id" => str_replace('$','a',$reg_clientes_id),
                        "reg_llave_secreta" => str_replace('$','o',$reg_llave_secreta)
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
