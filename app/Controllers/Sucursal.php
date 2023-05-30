<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\SucursalModel;
use App\Models\RegistrosModel;
class Sucursal extends Controller{

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
                    $model = new SucursalModel();
                    $sucursal = $model -> getSucursal();
                    if(!empty($sucursal)){
                        $data = array(
                            "Status" => 200, 
                            "Total de registros" => count($sucursal),
                            "Detalle" => $sucursal
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
                    $model = new SucursalModel();
                    $sucursal = $model -> getId($id);
                    //var_dump($curso); die;
                    if(!empty($sucursal)){
                        $data = array(
                            "Status" => 200,
                            "Detalle" => $sucursal
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
                            "sucu_nombre" => $request -> getVar("sucu_nombre"),
                            "sucu_direccion" => $request -> getVar("sucu_direccion"),
                            "sucu_telefono" => $request -> getVar("sucu_telefono"),
                            "sucu_departamento" => $request -> getVar("sucu_departamento"),
                            "sucu_provincia" => $request -> getVar("sucu_provincia"),
                            "empr_id" => $request -> getVar("empr_id")
                        );
                        if(!empty($datos)){
                            $validation -> setRules([
                                "sucu_nombre" => 'required|string|max_length[100]',
                                "sucu_direccion" => 'required|string|max_length[100]',
                                "sucu_telefono" => 'required|integer',
                                "sucu_departamento" => 'required|string|max_length[100]',
                                "sucu_provincia" => 'required|string|max_length[100]',
                                "empr_id" => 'required|integer'
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
                                    "sucu_nombre" => $datos["sucu_nombre"],
                                    "sucu_direccion" => $datos["sucu_direccion"],
                                    "sucu_telefono" => $datos["sucu_telefono"],
                                    "sucu_departamento" => $datos["sucu_departamento"],
                                    "sucu_provincia" => $datos["sucu_provincia"],
                                    "empr_id" => $datos["empr_id"]
                                );
                                $model = new SucursalModel();
                                $sucursal = $model -> insert($datos);
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
                            "sucu_nombre" => 'required|string|max_length[100]',
                            "sucu_direccion" => 'required|string|max_length[100]',
                            "sucu_telefono" => 'required|integer',
                            "sucu_departamento" => 'required|string|max_length[100]',
                            "sucu_provincia" => 'required|string|max_length[100]',
                            "empr_id" => 'required|integer'
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
                            $model = new SucursalModel();
                            $sucursal = $model -> find($id);
                            if(is_null($sucursal)){
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "Registro no existe"
                                );
                                return json_encode($data, true);
                            }else{
                                $datos = array(
                                    "sucu_nombre" => $datos["sucu_nombre"],
                                    "sucu_direccion" => $datos["sucu_direccion"],
                                    "sucu_telefono" => $datos["sucu_telefono"],
                                    "sucu_departamento" => $datos["sucu_departamento"],
                                    "sucu_provincia" => $datos["sucu_provincia"],
                                    "empr_id" => $datos["empr_id"]
                                );
                                $model = new SucursalModel();
                                $sucursal = $model -> update($id, $datos);
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
                    $model = new SucursalModel();
                    $sucursal = $model -> where('sucu_estado',1) -> find($id);
                    //var_dump($curso); die;
                    if(!empty($sucursal)){
                        $datos = array(
                            "sucu_estado" => 0
                        );
                        $sucursal = $model -> update($id, $datos);
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