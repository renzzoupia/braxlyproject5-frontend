<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\InventarioModel;
use App\Models\RegistrosModel;
class Inventario extends Controller{

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
                    $model = new InventarioModel();
                    $inventario = $model -> getInventario();
                    if(!empty($inventario)){
                        $data = array(
                            "Status" => 200, 
                            "Total de registros" => count($inventario),
                            "Detalle" => $inventario
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
                    $model = new InventarioModel();
                    $inventario = $model -> getId($id);
                    //var_dump($curso); die;
                    if(!empty($inventario)){
                        $data = array(
                            "Status" => 200,
                            "Detalle" => $inventario
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
                            "prod_id" => $request -> getVar("prod_id"),
                            "prov_id" => $request -> getVar("prov_id"),
                            "inv_tipo_movimiento" => $request -> getVar("inv_tipo_movimiento"),
                            "inv_cantidad" => $request -> getVar("inv_cantidad"),
                            "inv_fecha_ing" => $request -> getVar("inv_fecha_ing"),
                            "inv_fecha_vencimiento" => $request -> getVar("inv_fecha_vencimiento"),
                            "sucu_id" => $request -> getVar("sucu_id")
                        );
                        if(!empty($datos)){
                            $validation -> setRules([
                                "prod_id" => 'required|integer',
                                "prov_id" => 'required|integer',
                                "inv_tipo_movimiento" => 'required|string|max_length[100]',
                                "inv_cantidad" => 'required|string|max_length[100]',
                                "inv_fecha_ing" => 'required|date',
                                "inv_fecha_vencimiento" => 'required|date',
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
                                    "prod_id" => $datos["prod_id"],
                                    "prov_id" => $datos["prov_id"],
                                    "inv_tipo_movimiento" => $datos["inv_tipo_movimiento"],
                                    "inv_cantidad" => $datos["inv_cantidad"],
                                    "inv_fecha_ing" => $datos["inv_fecha_ing"],
                                    "inv_fecha_vencimiento" => $datos["inv_fecha_vencimiento"],
                                    "sucu_id" => $datos["sucu_id"]
                                );
                                $model = new InventarioModel();
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
                            "prod_id" => 'required|integer',
                            "prov_id" => 'required|integer',
                            "inv_tipo_movimiento" => 'required|string|max_length[100]',
                            "inv_cantidad" => 'required|string|max_length[100]',
                            "inv_fecha_ing" => 'required|date',
                            "inv_fecha_vencimiento" => 'required|date',
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
                            $model = new InventarioModel();
                            $inventario = $model -> find($id);
                            if(is_null($inventario)){
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "Registro no existe"
                                );
                                return json_encode($data, true);
                            }else{
                                $datos = array(
                                    "prod_id" => $datos["prod_id"],
                                    "prov_id" => $datos["prov_id"],
                                    "inv_tipo_movimiento" => $datos["inv_tipo_movimiento"],
                                    "inv_cantidad" => $datos["inv_cantidad"],
                                    "inv_fecha_ing" => $datos["inv_fecha_ing"],
                                    "inv_fecha_vencimiento" => $datos["inv_fecha_vencimiento"],
                                    "sucu_id" => $datos["sucu_id"]
                                );
                                $model = new InventarioModel();
                                $inventario = $model -> update($id, $datos);
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
        $registro = $model -> where('estado', 1) -> findAll();
        
        foreach($registro as $key => $value){
            if(array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])){
                if($request -> getHeader('Authorization') == 'Authorization: Basic '
                .base64_encode($value['reg_clientes_id'].':'.$value['reg_llave_secreta'])){
                    $model = new InventarioModel();
                    $inventario = $model -> where('inv_estado',1) -> find($id);
                    //var_dump($curso); die;
                    if(!empty($inventario)){
                        $datos = array(
                            "inv_estado" => 0
                        );
                        $inventario = $model -> update($id, $datos);
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