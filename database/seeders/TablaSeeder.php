<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TablaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i=1; $i <= 10; $i++) { 
           DB::table('usuarios')->insert([
            'nombre' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Str::random(6),
            'foto_perfil' => Str::random(9).'.jpg',
            ]);
            DB::table('cursos')->insert([
            'titulo_curso' => Str::random(10),
            'descripcion' => Str::random(10).'@gmail.com',
            'portada_curso' => Str::random(6),
            ]);
             DB::table('videos')->insert([
            'titulo_video' => Str::random(10),
            'enlace' => Str::random(10).'.com',
            'portada' => Str::random(6).'.jpg',
            'curso_id' => rand(1,$i),
            ]);
              DB::table('adquiridos')->insert([
            'usuario_id' => rand(1,$i),
            'curso_id' => rand(1,$i)
            ]);
        }
       
    }
}
