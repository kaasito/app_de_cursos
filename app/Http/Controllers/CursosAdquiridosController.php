<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adquirido;
use Illuminate\Support\Facades\DB;
//STATUS: 0->Error, 1->Exito
class CursosAdquiridosController extends Controller
{
     public function solicitar(Request $req)
    {
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);
        $cursoAdquirido = new Adquirido();
        $cursoAdquirido->usuario_id = $datos->usuario_id;
        $cursoAdquirido->curso_id = $datos->curso_id;
           try{
            $adquirido = Adquirido::where('usuario_id', $datos->usuario_id)->where('curso_id', $datos->curso_id)->value('id');
            if(!$adquirido){
            $cursoAdquirido->save();
            $respuesta["status"] = 1;
            $respuesta["msg"] = "Curso ".$datos->curso_id." adquirido con éxito para el ususario ".$datos->usuario_id;
            }else{
                $respuesta["status"] = 0;
                $respuesta["msg"] = "El usuario ya tiene este curso";
            }
       
        }catch(\Exception $e){
                $respuesta["msg"] = $e ->getMessage();
                $respuesta["status"] = 0;
        }
    return response()->json($respuesta);
    }

    public function miscursos(Request $req){
          $respuesta = ["status" => 1, "msg" => ""];
          $datos = $req->input('id', '');

        if($datos == ""){
                $respuesta["status"] = 0;
                $respuesta["msg"] = "No se ha efectuado la búsqueda: No se ha especificado id";
        }else{
            $adquirido = Adquirido::where('usuario_id', $datos)->select(['usuario_id', 'curso_id'])->get();
            if($adquirido){
                try{
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Mostrando todos los cursos del usuario con id: ".$datos;
                
                $respuesta["Datos"] = DB::table('adquiridos')
                ->join('usuarios', 'usuarios.id', '=', 'adquiridos.usuario_id')
                ->join('cursos', 'cursos.id', '=', 'adquiridos.curso_id')
                ->where('adquiridos.usuario_id', $datos)
                ->select('cursos.titulo_curso as Nombre del Curso')
                ->get();
            }catch(\Exception $e){
                $respuesta["msg"] = $e ->getMessage();
                $respuesta["status"] = 0;
            }
                }

            

                    }
        return response()->json($respuesta);
    }

  
}
