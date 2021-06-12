<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Nivel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class NivelController extends Controller
{
    private function validateKey(string $key){
        return $key == env("NIVEL_KEY");
    }

    public function createNivel(Request $request){

        $data = $request->all();
        

        if($this->validateKey($data["key"])){
            DB::transaction(function() use($data, &$response){

                Nivel::create([
                    "nome" => $data["nome"]
                ]);
                
                $response = [
                    "success" => true,
                    "log" => false,
                    "message" => "Nivel criado com sucesso!"
                ];

            });


            return Response::json($response, 200);
        }else{
            return Response::json([
                "error" =>  true,
                "log" => false,
                "message" => "Sem permissoes para executar essa tarefa!"
            ], 401);
        }

    }
}
