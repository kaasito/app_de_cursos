<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Video;
use App\Models\Adquirido;
use App\Models\Visto;
use Illuminate\Support\Facades\DB;

//STATUS: 0->Error, 1->Exito
class CursosController extends Controller
{
    public function crear(Request $req){
        
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);
        $curso = new Curso();
        $curso->titulo_curso = $datos->titulo_curso;
        $curso->descripcion = $datos->descripcion;
        $curso->portada_curso = $datos->portada_curso;
        try{
            $curso->save();
            $respuesta["status"] = 1;
            $respuesta["msg"] = "Curso creado con éxito";
        }catch(\Exception $e){
            $respuesta["status"] = 0;
            $respuesta["msg"] = $e ->getMessage();
            
        }
        return response()->json($respuesta);
    }
    
    
    
    public function listar(Request $req){
        
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->input('filtro', '');
        $condicion = $req->input('condicion', '');
        $videos = $req->input('videos', '');
        

        try{
            $peticion = DB::table('cursos')
                        ->leftJoin('videos', 'videos.curso_id', '=', 'cursos.id');

            if($datos != '') {
                $peticion->where('titulo_curso', 'like', '%'.$datos.'%');
            }

            if(!$condicion) {
                $condicion = '=';
            }

            if($videos != '') {
                $peticion->where(DB::raw('(SELECT COUNT(videos.id) FROM videos WHERE videos.curso_id = cursos.id)'), $condicion, $videos);
            }


            $peticion->select('titulo_curso', 'portada_curso', DB::raw('(SELECT COUNT(videos.id) FROM videos WHERE videos.curso_id = cursos.id) as Videos'));

            $respuesta["status"] = 1;
            $respuesta["msg"] = "Mostrando todos los cursos";
            $respuesta["datos"] = $peticion->distinct()->get();

        }catch(\Exception $e){
            $respuesta["msg"] = $e ->getMessage();
            $respuesta["status"] = 0;
        }

/*
        if($datos == ""){
            try{
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Mostrandon todos los cursos";
                 $respuesta["datos"] = DB::table('cursos')
                ->leftJoin('videos', 'videos.curso_id', '=', 'cursos.id')
                ->select('titulo_curso', 'portada_curso', DB::raw('(SELECT COUNT(videos.id) FROM videos WHERE videos.curso_id = cursos.id) as Videos'))->distinct()->get();
            }catch(\Exception $e){
                $respuesta["msg"] = $e ->getMessage();
                $respuesta["status"] = 0;
            }
        }else{
            try{
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Mostrandon todos los cursos con el filtro ".$datos;
                $respuesta["datos"] = Curso::where('titulo_curso', 'like', '%'.$datos.'%')
                            ->leftJoin('videos', 'videos.curso_id', '=', 'cursos.id')
                            ->select('titulo_curso', 'portada_curso', DB::raw('(SELECT COUNT(videos.id) FROM videos WHERE videos.curso_id = cursos.id) as Videos'))
                            ->get();
            }catch(\Exception $e){
                $respuesta["msg"] = $e ->getMessage();
                $respuesta["status"] = 0;
            }
        }
*/

        return response()->json($respuesta);
    }
    
    public function vervideo(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];
        
        $usuario_id = $req->input('usuario_id', 0);
        $curso_id = $req->input('curso_id', 0);
        $adquirido = Adquirido::where('usuario_id', $usuario_id)->where('curso_id', $curso_id)->value('id');
        
        if($adquirido){
            
            try{
                $respuesta["status"] = 1;
                $respuesta["msg"] = "Videos del curso ".$curso_id;
                $video = Video::where('curso_id', $curso_id)->value('id');
                //$respuesta["video"] = Video::where('curso_id', $curso_id)->select(['titulo_video', 'portada'])->get();
                $respuesta["video"] = DB::table('videos')
                ->leftJoin('vistos', 'videos.id', '=', 'vistos.video_id')
                ->select('videos.titulo_video as Titulo Video', 'videos.portada as Portada', 'vistos.created_at as visto')
                ->get();
                
            }catch(\Exception $e){
                $respuesta["msg"] = $e ->getMessage();
                $respuesta["status"] = 0;
            }
            
        }else{
        $respuesta["status"] = 0;
        $respuesta["msg"] = "El usuario no tiene ese curso";
    }
        return response()->json($respuesta);     
        
    }
}
