<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PedidosModel;
use App\Models\RegistrosModel;
class Pedidos extends Controller{

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
                    $model = new PedidosModel();
                    $pedidos = $model -> getPedidos();
                    if(!empty($pedidos)){
                        $data = array(
                            "Status" => 200, 
                            "Total de registros" => count($pedidos),
                            "Detalle" => $pedidos
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
                    $model = new PedidosModel();
                    $pedidos = $model -> getId($id);
                    //var_dump($curso); die;
                    if(!empty($pedidos)){
                        $data = array(
                            "Status" => 200,
                            "Detalle" => $pedidos
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
                            "ped_num_pedido" => $request -> getVar("ped_num_pedido"),
                            "ped_tipo_compra" => $request -> getVar("ped_tipo_compra"),
                            "ped_estado_pedido" => $request -> getVar("ped_estado_pedido"),
                            "ped_detalles" => $request -> getVar("ped_detalles"),
                            "pla_id" => $request -> getVar("pla_id"),
                            "cli_id" => $request -> getVar("cli_id")
                        );
                        if(!empty($datos)){
                            $validation -> setRules([
                                "ped_num_pedido" => 'required|integer',
                                "ped_tipo_compra" => 'required|string|max_length[100]',
                                "ped_estado_pedido" => 'required|string|max_length[100]',
                                "ped_detalles" => 'required|string|max_length[255]',
                                "pla_id" => 'required|integer',
                                "cli_id" => 'required|integer'
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
                                    "ped_num_pedido" => $datos["ped_num_pedido"],
                                    "ped_tipo_compra" => $datos["ped_tipo_compra"],
                                    "ped_estado_pedido" => $datos["ped_estado_pedido"],
                                    "ped_detalles" => $datos["ped_detalles"],
                                    "pla_id" => $datos["pla_id"],
                                    "cli_id" => $datos["cli_id"]
                                );
                                $model = new PedidosModel();
                                $pedidos = $model -> insert($datos);
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
                            "ped_num_pedido" =>'required|integer',
                            "ped_tipo_compra" => 'required|string|max_length[100]',
                            "ped_estado_pedido" => 'required|string|max_length[100]',
                            "ped_detalles" => 'required|string|max_length[255]',
                            "pla_id" => 'required|integer',
                            "cli_id " => 'required|integer'
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
                            $model = new PedidosModel();
                            $pedidos = $model -> find($id);
                            if(is_null($pedidos)){
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "Registro no existe"
                                );
                                return json_encode($data, true);
                            }else{
                                $datos = array(
                                    "ped_num_pedido" => $datos["ped_num_pedido"],
                                    "ped_tipo_compra" => $datos["ped_tipo_compra"],
                                    "ped_estado_pedido" => $datos["ped_estado_pedido"],
                                    "ped_detalles" => $datos["ped_detalles"],
                                    "pla_id" => $datos["pla_id"],
                                    "cli_id" => $datos["cli_id"]
                                );
                                $model = new PedidosModel();
                                $pedidos = $model -> update($id, $datos);
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
                    $model = new PedidosModel();
                    $pedidos = $model -> where('ped_estado',1) -> find($id);
                    //var_dump($curso); die;
                    if(!empty($pedidos)){
                        $datos = array(
                            "ped_estado" => 0
                        );
                        $pedidos = $model -> update($id, $datos);
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