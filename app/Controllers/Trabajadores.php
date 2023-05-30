<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\TrabajadoresModel;
use App\Models\RegistrosModel;
class Trabajadores extends Controller{
    public function index(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('reg_estado', 1)->findAll();
         
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new TrabajadoresModel();
                    $trabajador = $model->getTrabajadores();
                    if(!empty($trabajador)){
                        $data = array(
                            "Status"=>200,
                            "Total de registros"=>count($trabajador), 
                            "Detalle"=>$trabajador);
                    }
                    else{
                        $data = array(
                            "Status"=>404,
                            "Total de registros"=>0, 
                            "Detalle"=>"No hay registros");
                    }
                    return json_encode($data, true);
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);        
    }
    public function show($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('reg_estado', 1)->findAll();
         
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new TrabajadoresModel();
                    $trabajador = $model->getId($id);
                    if(!empty($trabajador)){
                        $data = array(
                            "Status"=>200, "Detalle"=>$trabajador);
                    }
                    else{
                        $data = array(
                            "Status"=>404, "Detalle"=>"No hay registros");
                    }
                    return json_encode($data, true);
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }

    public function create(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request -> getHeaders();
        $model = new RegistrosModel();
        $registro = $model -> where('reg_estado', 1) -> findAll();
        //var_dump($registro); die;
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request -> getHeader('Authorization') == 'Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                        $datos = array(
                            "per_id" => $request -> getVar("per_id"),
                            "tra_sueldo" => $request -> getVar("tra_sueldo"),
                            "titra_id" => $request -> getVar("titra_id"),
                            "sucu_id" => $request -> getVar("sucu_id")
                        );
                        if(!empty($datos)){
                            $validation -> setRules([
                                "per_id" => 'required|integer',
                                "tra_sueldo" => 'required|integer',
                                "titra_id" => 'required|integer',
                                "sucu_id" => 'required|integer'
                            ]);
                            $validation -> withRequest($this -> request) -> run();
                            if($validation -> getErrors()){
                                $errors = $validation -> getErrors();
                                $data = array(
                                    "Status" => 404,
                                    "Detalle" => $errors
                                );
                                return json_encode($data, true);
                            }else{
                                $datos = array(
                                    "per_id" => $datos["per_id"],
                                    "tra_sueldo" => $datos["tra_sueldo"],
                                    "titra_id" => $datos["titra_id"],
                                    "sucu_id" => $datos["sucu_id"]
                                );
                                $model = new TrabajadoresModel();
                                $productos = $model -> insert($datos);
                                $data = array(
                                    "Status" => 200,
                                    "Detalle" => "Registro existoso"
                                );
                                return json_encode($data, true);
                            }
                        }else{
                            $data = array(
                                "Status" => 404,
                                "Detalle" => "Registro con errores"
                            );
                            return json_encode($data, true);
                        }
                }else{
                    $data = array(
                        "Status" => 404,
                        "Detalles" => "El token es incorrecto"
                    );
                }
            }else{
                $data = array(
                    "Status" => 404,
                    "Detalles" => "No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }
    public function update($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('reg_estado', 1)->findAll();
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                        
                        $datos = $this->request->getRawInput();
                        
                        if(!empty($datos)){
                             $validation->setRules([
                                "per_id"=>'required|integer',
                                "tra_sueldo"=>'required|integer',
                                "titra_id"=>'required|integer',
                                "sucu_id"=>'required|integer'
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array("Status"=>404, "Detalle"=>$errors);
                                return json_encode($data, true);
                            }
                            else{
                                $model = new TrabajadoresModel();
                                $trabajador = $model->find($id);
                                if(is_null($trabajador)){
                                    $data = array(
                                        "Status"=>404,
                                        "Detalles"=>"Registro no existe"
                                    );
                                    return json_encode($data, true);
                                }
                                else{
                                    $datos = array(
                                        "per_id"=>$datos["per_id"],
                                        "tra_sueldo"=>$datos["tra_sueldo"],
                                        "titra_id"=>$datos["titra_id"],
                                        "sucu_id"=>$datos["sucu_id"]
                                    );
                                    $model = new TrabajadoresModel();
                                    $trabajador = $model->update($id, $datos);
                                    $data = array(
                                        "Status"=>200,
                                        "Detalles"=>"Datos actualizados"
                                    );
                                    return json_encode($data, true);
                                }
                            }
                        }
                        else{
                            $data = array(
                                "Status"=>404,
                                "Detalle"=>"Registro con errores"
                            );
                            return json_encode($data, true);
                        }
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }
    public function delete($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('reg_estado', 1)->findAll();
         
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new TrabajadoresModel();
                    $trabajador = $model->where('tra_estado',1)->find($id);
                    if(!empty($trabajador)){
                        $datos = array("tra_estado"=>0);
                        $trabajador = $model->update($id, $datos);
                        $data = array(
                            "Status"=>200,
                            "Detalle"=>"Se ha eliminado el registro"
                        );
                    }
                    else{
                        $data = array(
                            "Status"=>404, 
                            "Detalle"=>"No hay registros");
                    }
                    return json_encode($data, true);
                }
                else{
                    $data = array(
                        "Status"=>404,
                        "Detalles"=>"El token es incorrecto"
                    );
                }
            }
            else{
                $data = array(
                    "Status"=>404,
                    "Detalles"=>"No posee autorización"
                );
            }
        }
        return json_encode($data, true);
    }
}