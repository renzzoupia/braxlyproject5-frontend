<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DetallePedidoModel;
use App\Models\RegistrosModel;
class detallepedido extends Controller{

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
                    $model = new DetallePedidoModel();
                    $detallePedido = $model -> getDetallePedido();
                    if(!empty($detallePedido)){
                        $data = array(
                            "Status" => 200, 
                            "Total de registros" => count($detallePedido),
                            "Detalle" => $detallePedido
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
        var_dump($registro); die;
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request -> getHeader('Authorization') == 'Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new DetallePedidoModel();
                    $detallePedido = $model -> getId($id);
                    //var_dump($detallePedido); die;
                    if(!empty($detallePedido)){
                        $data = array(
                            "Status" => 200,
                            "Detalle" => $detallePedido
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
                            "ped_id" => $request -> getVar("ped_id"),
                            "depe_estado_comida" => $request -> getVar("depe_estado_comida"),
                            "depe_fecha" => $request -> getVar("depe_fecha"),
                            "tipa_id" => $request -> getVar("tipa_id"),
                            "depe_num_mesa" => $request -> getVar("depe_num_mesa"),
                            "deco_info_compra" => $request -> getVar("deco_info_compra"),
                            "tra_id" => $request -> getVar("tra_id"),
                            "ticon_id" => $request -> getVar("ticon_id")
                        );
                        if(!empty($datos)){
                            $validation -> setRules([
                                "ped_id" =>'required|integer',
                                "depe_estado_comida" => 'required|string|max_length[255]',
                                "depe_fecha" => 'required|date',
                                "tipa_id" => 'required|integer',
                                "depe_num_mesa" => 'required|string|max_length[100]',
                                "deco_info_compra" => 'required|string|max_length[100]',
                                "tra_id" => 'required|integer',
                                "ticon_id" => 'required|integer'
                                
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
                                    "ped_id" => $datos["ped_id"],
                                    "depe_estado_comida" => $datos["depe_estado_comida"],
                                    "depe_fecha" => $datos["depe_fecha"],
                                    "tipa_id" => $datos["tipa_id"],
                                    "depe_num_mesa" => $datos["depe_num_mesa"],
                                    "deco_info_compra" => $datos["deco_info_compra"],
                                    "tra_id" => $datos["tra_id"],
                                    "ticon_id" => $datos["ticon_id"]
                                );
                                $model = new DetallePedidoModel();
                                $detallePedido = $model -> insert($datos);
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
                            "ped_id" =>'required|integer',
                                "depe_estado_comida" => 'required|string|max_length[255]',
                                "depe_fecha" => 'required|date',
                                "tipa_id" => 'required|integer',
                                "depe_num_mesa" => 'required|string|max_length[100]',
                                "deco_info_compra" => 'required|string|max_length[100]',
                                "tra_id" => 'required|integer',
                                "ticon_id" => 'required|integer'
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
                            $model = new DetallePedidoModel();
                            $detallePedido = $model -> find($id);
                            if(is_null($detallePedido)){
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "Registro no existe"
                                );
                                return json_encode($data, true);
                            }else{
                                $datos = array(
                                    "ped_id" => $datos["ped_id"],
                                    "depe_estado_comida" => $datos["depe_estado_comida"],
                                    "depe_fecha" => $datos["depe_fecha"],
                                    "tipa_id" => $datos["tipa_id"],
                                    "depe_num_mesa" => $datos["depe_num_mesa"],
                                    "deco_info_compra" => $datos["deco_info_compra"],
                                    "tra_id" => $datos["tra_id"],
                                    "ticon_id" => $datos["ticon_id"]
                                );
                                $model = new DetallePedidoModel();
                                $detallePedido = $model -> update($id, $datos);
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
                    $model = new DetallePedidoModel();
                    $detallePedido = $model -> where('depe_estado',1) -> find($id);
                    //var_dump($curso); die;
                    if(!empty($detallePedido)){
                        $datos = array(
                            "depe_estado" => 0
                        );
                        $detallePedido = $model -> update($id, $datos);
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