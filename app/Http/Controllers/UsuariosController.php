<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
//STATUS: 0->Error, 1->Exito
class UsuariosController extends Controller
{
    public function crear(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);
        $usuario = new Usuario();
        $usuario->nombre = $datos->nombre;
        $usuario->email = $datos->email;
        $usuario->password = $datos->password;
        $usuario->foto_perfil = $datos->foto_perfil;
        try{
            $respuesta["status"] = 1;
            $respuesta["msg"] = "Usuario creado con éxito";
            $usuario->save();
        }catch(\Exception $e){
            $respuesta["msg"] = $e ->getMessage();
            $respuesta["status"] = 0;
        }
        return response()->json($respuesta);
    }
    
    
    public function modificar(Request $req, $id){
        
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);
        $usuario = Usuario::find($id);  
        if($usuario){
            if(isset($datos->email)){
                $respuesta["status"] = 0;
                $respuesta["msg"] = "Datos no editados: no se puede editar el email";
            }else{
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Datos  editados con éxito";
                if(isset($datos->nombre)){
                    $usuario->nombre = $datos->nombre;
                }
                if(isset($datos->password)){
                    $usuario->password = $datos->password;
                }
                if(isset($datos->foto_perfil)){
                    $usuario->foto_perfil = $datos->foto_perfil;
                }
                 if(isset($datos->activo)){
                    $usuario->activo = $datos->activo;
                }
            }
            try{
                $usuario->save();
            }catch(\Exception $e){
                $respuesta["msg"] = $e ->getMessage();
                $respuesta["status"] = 0;
            }
            return response()->json($respuesta);
        }
        die('vale');
    }
}
