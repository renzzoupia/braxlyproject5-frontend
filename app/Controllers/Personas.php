<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PersonasModel;
use App\Models\RegistrosModel;
class Personas extends Controller{
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
                    $model = new PersonasModel();
                    $personas = $model->where('per_estado',1)->findAll();
                    if(!empty($personas)){
                        $data = array(
                            "Status"=>200,
                            "Total de registros"=>count($personas), 
                            "Detalle"=>$personas);
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
                    $model = new PersonasModel();
                    $personas = $model->where('per_estado',1)->find($id);
                    if(!empty($personas)){
                        $data = array(
                            "Status"=>200, "Detalle"=>$personas);
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
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('reg_estado', 1)->findAll();
        // var_dump($registro); die; 
        foreach($registro as $key=>$value){
            if(array_key_exists('Authorization',$headers) && !empty($headers['Authorization'])){
                if($request->getHeader('Authorization')=='Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                        $datos = array(
                            "per_nombres"=>$request->getVar("per_nombres"),
                            "per_apellidos"=>$request->getVar("per_apellidos"),
                            "per_telefono"=>$request->getVar("per_telefono"),
                            "per_dni"=>$request->getVar("per_dni"),
                            "per_correo"=>$request->getVar("per_correo")
                        );
                        if(!empty($datos)){
                            $validation->setRules([
                                "per_nombres"=>'required|string|max_length[255]',
                                "per_apellidos"=>'required|max_length[255]',
                                "per_telefono"=>'required|string|max_length[100]',
                                "per_dni"=>'required|string|max_length[100]',
                                "per_correo"=>'required|valid_email'
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array(
                                    "Status"=>404,
                                    "Detalle"=>$errors
                                );
                                return json_encode($data, true);
                            }
                            else{
                                $datos = array(
                                    "per_nombres"=>$datos["per_nombres"],
                                    "per_apellidos"=>$datos["per_apellidos"],
                                    "per_telefono"=>$datos["per_telefono"],
                                    "per_dni"=>$datos["per_dni"],
                                    "per_correo"=>$datos["per_correo"]
                                );
                                $model = new PersonasModel();
                                $persona = $model->insert($datos);
                                $data = array(
                                    "Status"=>200,
                                    "Detalle"=>"Registro existoso"
                                );
                                return json_encode($data, true);
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
                                "per_nombres"=>'required|string|max_length[255]',
                                "per_apellidos"=>'required|max_length[255]',
                                "per_telefono"=>'required|string|max_length[100]',
                                "per_dni"=>'required|string|max_length[100]',
                                "per_correo"=>'required|valid_email'
                            ]);
                            $validation->withRequest($this->request)->run();
                            if($validation->getErrors()){
                                $errors = $validation->getErrors();
                                $data = array(
                                    "Status"=>404,
                                    "Detalle"=>$errors
                                );
                                return json_encode($data, true);
                            }
                            else{
                                $model = new PersonasModel();
                                $persona = $model->find($id);
                                if(is_null($persona)){
                                    $data = array(
                                        "Status"=>404,
                                        "Detalles"=>"Registro no existe"
                                    );
                                    return json_encode($data, true);
                                }
                                else{
                                    $datos = array(
                                    "per_nombres"=>$datos["per_nombres"],
                                    "per_apellidos"=>$datos["per_apellidos"],
                                    "per_telefono"=>$datos["per_telefono"],
                                    "per_dni"=>$datos["per_dni"],
                                    "per_correo"=>$datos["per_correo"]
                                    );
                                    $model = new PersonasModel();
                                    $persona = $model->update($id, $datos);
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
                    $model = new PersonasModel();
                    $persona = $model->where('per_estado',1)->find($id);
                    if(!empty($persona)){
                        $datos = array("per_estado"=>0);
                        $persona = $model->update($id, $datos);
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