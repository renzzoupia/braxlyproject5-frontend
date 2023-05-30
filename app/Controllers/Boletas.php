<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\BoletasModel;
use App\Models\RegistrosModel;
class Boletas extends Controller{

    public function index(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request->getHeaders();
        $model = new RegistrosModel();
        $registro = $model->where('reg_estado', 1) -> findAll();
        
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization',$headers)&& !empty($headers['Authorization'])){
                if($request -> getHeader('Authorization') == 'Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new BoletasModel();
                    $boletas = $model -> getBoletas();
                    if(!empty($boletas)){
                        $data = array(
                            "Status" => 200, 
                            "Total de registros" => count($boletas),
                            "Detalle" => $boletas
                        );
                    }else{
                        $data = array(
                            "Status" => 404,
                            "Total de registros" => 0,
                            "Detalles" => "No hay registros"
                        );
                    }
                    return json_encode($data, true);
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

    public function show($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request -> getHeaders();
        $model = new RegistrosModel();
        $registro = $model -> where('reg_estado', 1) -> findAll();
        
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                
                if($request -> getHeader('Authorization') == 'Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new BoletasModel();
                    $boletas = $model -> getId($id);
                    //var_dump($curso); die;
                    if(!empty($boletas)){
                        $data = array(
                            "Status" => 200,
                            "Detalle" => $boletas
                        );
                    }else{
                        $data = array(
                            "Status" => 404,
                            "Detalles" => "No hay registros"
                        );
                    }
                    return json_encode($data, true);
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
                            "tra_id" => $request -> getVar("tra_id"),
                            "bol_fech_emision" => $request -> getVar("bol_fech_emision"),
                            "bol_monto_total" => $request -> getVar("bol_monto_total"),
                            "deco_info_compra" => $request -> getVar("deco_info_compra"),
                            "depe_id" => $request -> getVar("depe_id")
                        );
                        if(!empty($datos)){
                            $validation -> setRules([
                                "tra_id" => 'required|integer',
                                "bol_fech_emision" => 'required|date',
                                "bol_monto_total" => 'required|string|max_length[100]',
                                "deco_info_compra" => 'required|string|max_length[255]',
                                "depe_id" => 'required|integer'
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
                                    "tra_id" => $datos["tra_id"],
                                    "bol_fech_emision" => $datos["bol_fech_emision"],
                                    "bol_monto_total" => $datos["bol_monto_total"],
                                    "deco_info_compra" => $datos["deco_info_compra"],
                                    "depe_id" => $datos["depe_id"]
                                );
                                $model = new BoletasModel();
                                $curso = $model -> insert($datos);
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
        $headers = $request -> getHeaders();
        $model = new RegistrosModel();
        $registro = $model -> where('reg_estado', 1) -> findAll();
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request -> getHeader('Authorization') == 'Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $datos = $this -> request -> getRawInput();
                    if(!empty($datos)){
                        $validation -> setRules([
                          "tra_id" => 'required|integer',
                          "bol_fech_emision" => 'required|date',
                          "bol_monto_total" => 'required|string|max_length[100]',
                          "deco_info_compra" => 'required|string|max_length[255]',
                          "depe_id" => 'required|integer'
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
                            $model = new BoletasModel();
                            $boletas = $model -> find($id);
                            if(is_null($boletas)){
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "Registro no existe"
                                );
                                return json_encode($data, true);
                            }else{
                                $datos = array(
                                  "tra_id" => $datos["tra_id"],
                                  "bol_fech_emision" => $datos["bol_fech_emision"],
                                  "bol_monto_total" => $datos["bol_monto_total"],
                                  "deco_info_compra" => $datos["deco_info_compra"],
                                  "depe_id" => $datos["depe_id"]
                                );
                                $model = new BoletasModel();
                                $boletas = $model -> update($id, $datos);
                                $data = array(
                                    "Status" => 200,
                                    "Detalles" => "Datos actualizados"
                                );
                                return json_encode($data, true);
                            }
                        }
                    }else{
                        $data = array(
                            "Status" => 400,
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

    public function delete($id){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $headers = $request -> getHeaders();
        $model = new RegistrosModel();
        $registro = $model -> where('reg_estado', 1) -> findAll();
        
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request -> getHeader('Authorization') == 'Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new BoletasModel();
                    $boletas = $model -> where('bol_estado',1) -> find($id);
                    //var_dump($curso); die;
                    if(!empty($boletas)){
                        $datos = array(
                            "bol_estado" => 0
                        );
                        $boletas = $model -> update($id, $datos);
                        $data = array(
                            "Status" => 200, 
                            "Detalle" => "Se ha eliminado el registro"
                        );
                    }else{
                        $data = array(
                            "Status" => 404,
                            "Detalles" => "No hay registros"
                        );
                    }
                    return json_encode($data, true);
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
}