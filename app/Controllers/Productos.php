<?php   
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ProductosModel;
use App\Models\RegistrosModel;
class Productos extends Controller{

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
                    $model = new ProductosModel();
                    $productos = $model -> getProductos();
                    if(!empty($productos)){
                        $data = array(
                            "Status" => 200, 
                            "Total de registros" => count($productos),
                            "Detalle" => $productos
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
                    $model = new ProductosModel();
                    $productos = $model -> getId($id);
                    //var_dump($curso); die;
                    if(!empty($productos)){
                        $data = array(
                            "Status" => 200,
                            "Detalle" => $productos
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
                            "prod_nombre" => $request -> getVar("prod_nombre"),
                            "prod_descripcion" => $request -> getVar("prod_descripcion"),
                            "prod_precio" => $request -> getVar("prod_precio"),
                            "tipr_id" => $request -> getVar("tipr_id"),
                            "prod_unidad" => $request -> getVar("prod_unidad")
                        );
                        if(!empty($datos)){
                            $validation -> setRules([
                                "prod_nombre" => 'required|string|max_length[100]',
                                "prod_descripcion" => 'required|string|max_length[255]',
                                "prod_precio" => 'required|string|max_length[50]',
                                "tipr_id" => 'required|integer',
                                "prod_unidad" => 'required|string|max_length[50]'
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
                                    "prod_nombre" => $datos["prod_nombre"],
                                    "prod_descripcion" => $datos["prod_descripcion"],
                                    "prod_precio" => $datos["prod_precio"],
                                    "tipr_id" => $datos["tipr_id"],
                                    "prod_unidad" => $datos["prod_unidad"]
                                );
                                $model = new ProductosModel();
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
                                "prod_nombre" => 'required|string|max_length[100]',
                                "prod_descripcion" => 'required|string|max_length[255]',
                                "prod_precio" => 'required|string|max_length[50]',
                                "tipr_id" => 'required|integer',
                                "prod_unidad" => 'required|string|max_length[50]'
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
                            $model = new ProductosModel();
                            $productos = $model -> find($id);
                            if(is_null($productos)){
                                $data = array(
                                    "Status" => 404,
                                    "Detalles" => "Registro no existe"
                                );
                                return json_encode($data, true);
                            }else{
                                $datos = array(
                                    "prod_nombre" => $datos["prod_nombre"],
                                    "prod_descripcion" => $datos["prod_descripcion"],
                                    "prod_precio" => $datos["prod_precio"],
                                    "tipr_id" => $datos["tipr_id"],
                                    "prod_unidad" => $datos["prod_unidad"]
                                );
                                $model = new ProductosModel();
                                $productos = $model -> update($id, $datos);
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
                    $model = new ProductosModel();
                    $productos = $model -> where('prod_estado',1) -> find($id);
                    //var_dump($curso); die;
                    if(!empty($productos)){
                        $datos = array(
                            "prod_estado" => 0
                        );
                        $productos = $model -> update($id, $datos);
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