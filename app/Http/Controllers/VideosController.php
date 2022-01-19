<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Adquirido;
use App\Models\Visto;


class VideosController extends Controller
{
    public function crear(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];
        $datos = $req->getContent();
        $datos = json_decode($datos);
        $video = new Video();
        $video->titulo_video = $datos->titulo_video;
        $video->enlace = $datos->enlace;
        $video->portada = $datos->portada;
        $video->curso_id = $datos->curso_id;
        try{
            $respuesta["status"] = 1;
            $respuesta["msg"] = "Video creado con éxito";
            $video->save();
        }catch(\Exception $e){
            $respuesta["msg"] = $e ->getMessage();
            $respuesta["status"] = 0;
        }
        return response()->json($respuesta);
    }
    public function vervideo(Request $req){
        $respuesta = ["status" => 1, "msg" => ""];

        $usuario_id = $req->input('usuario_id', 0);
        $video_id = $req->input('video_id', 0);

        try{
        $curso_id = Video::where('id', $video_id)->value('curso_id'); //devuelve el valor del id del curso al que pertenece el video
        $adquirido = Adquirido::where('usuario_id', $usuario_id)->where('curso_id', $curso_id)->value('id');
        $visto_b = Visto::where('usuario_id', $usuario_id)->where('video_id', $video_id)->value('id');

        if($adquirido){
            if(!$visto_b){
            $visto = new Visto();
            $visto->usuario_id = $usuario_id;
            $visto->video_id = $video_id;
            $visto->save();
            $respuesta["status"] = 1;
            $respuesta["datos"] = Video::where('id', $video_id)->select(['id', 'enlace'])->get();
        }else{
            $respuesta["status"] = 1;
            $respuesta["datos"] = Video::where('id', $video_id)->select(['id', 'enlace'])->get();
            $respuesta["msg"] = "El video ya está visto";
        }
            
        }else{
        $respuesta["status"] = 0;
        $respuesta["msg"] = "El usuario no tiene ese curso";
        }
        }catch(\Exception $e){
            $respuesta["msg"] = $e ->getMessage();
            $respuesta["status"] = 0;
        }
         return response()->json($respuesta);

    }
}
