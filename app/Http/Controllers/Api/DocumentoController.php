<?php

namespace App\Http\Controllers\Api;

use App\Documento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\User;
use App\Log;
use App\Exports\VacinadosExport;
use App\Vacinado;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Facades\JWTAuth;
use \PDF;

class DocumentoController extends Controller
{   

    private function createLog($auth, $message = null, $registro = null, $table = null, $type = null){

        if (!$auth instanceof User) {
            throw new \Exception("O usuario não tem permissao para continuar. reference log", 401);
        }

        $messagePadrao = [
            'Update' => 'O usuario ' . $auth->nome . ' correspondente ao id: ' . $auth->id  . ' atualizou o registro ' . $registro->id . ' na tabela ' . $table . ' as ' . $registro->updated_at->format('H:i:s') . ' do dia ' . $registro->updated_at->format('d/m/Y') . '.',
            'Post' => 'O usuario ' . $auth->nome . ' correspondente ao id: ' . $auth->id  . ' criou um novo registro correspondente ao id: ' . $registro->id . ' na tabela ' . $table . ' as ' . $registro->created_at->format('H:i:s') . ' do dia ' . $registro->created_at->format('d/m/Y') . '.',
            'Delete' => 'O usuario ' . $auth->nome . ' correspondente ao id: ' . $auth->id  . ' deletou um registro correspondente ao id: ' . $registro->id . ' na tabela ' . $table . ' as ' . $registro->updated_at->format('H:i:s') . ' do dia ' . $registro->updated_at->format('d/m/Y') . '.'
        ];


        if (is_null($message)) {
            DB::transaction(function () use ($messagePadrao, $auth, $type, &$response) {
                Log::create([
                    'log_message' => $messagePadrao[$type],
                    'user_id' => $auth->id
                ]);

                $response = true;
            });
        } else {
            DB::trasaction(function () use ($auth, $message, &$response) {
                Log::create([
                    'log_message' => $message,
                    'user_id' => $auth->id
                ]);
                $response = true;
            });
        }


        return $response;
    }


    public function store(Request $request){
        $data = $request->all();
        
        if(!isset($data["type"]) || !$data["type"]){
            return Response::json([
                "error" => true,
                "message" => "Algo de errado aconteceu, tente novamente mais tarde!"
            ], 400);
        }

        if($data["type"] !== 'xlsx' && $data["type"] !== 'pdf'){
            return Response::json([
                "error" => true,
                "message" => "Param type incorreto!"
            ], 400);
        }

        $name_file = md5('Vacinados'. time());

        
        DB::transaction(function() use($data, $name_file,$request, &$response) {
            if($data["type"] == "xlsx"){
                (new VacinadosExport($request))->store("" . $name_file .".xlsx");
            }else{

                $where = function($q) use($data){
        
                    if(isset($data["pais"]) && $data["pais"]){
                        $q->where("pais", $data["pais"]);
                    }
        
                    if(isset($data["vacinado"]) && $data["vacinado"]){
                        $q->where("vacinado", $data["vacinado"]);
                    }
        
                    if(isset($data["assintomatico"]) && $data["assintomatico"]){
                        $q->where("assintomatico", $data["assintomatico"]);
                    }
        
                    if(isset($data["infectado"]) && $data["infectado"]){
                        $q->where("infectado", $data["infectado"]);
                    }
        
                    if(isset($data["bebida"]) && $data["bebida"]){
                        $q->where("bebida", $data["bebida"]);
                    }

                    if(isset($data['curso']) && $data['curso']){
                        $q->where('curso', $data['curso']);
                    }
        
                    if(isset($data['turma']) && $data['turma']){
                        $q->where('turma', $data['turma']);
                    }
        
                    if(isset($data['turno']) && $data['turno']){
                        $q->where('turno', $data['turno']);
                    }
        
                    if(isset($data["user_id"]) && $data["user_id"]){
                        $q->where("user_id", $data["user_id"]);
                    }
        
                    if(isset($data["sexo"]) && $data["sexo"]){
                        $q->where("sexo", $data["sexo"]);
                    }
        
                    if (isset($data['data_in']) && $data['data_in']) {
                        $dataInicio = Carbon::createFromFormat('d/m/Y', $data['data_in'])->setTime(0, 0, 0)->format('Y-m-d H:i:s');
                        $q->where('created_at', '>=', $dataInicio);
                    }
        
                    if (isset($data['data_fim']) && $data['data_fim']) {
                        $dataFim = Carbon::createFromFormat('d/m/Y', $data['data_fim'])->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                        $q->where('created_at', '<=', $dataFim);
                    }
                };

                $vacinados_pdf = Vacinado::where($where)->get();
                $pdf = PDF::loadView('pdf', compact('vacinados_pdf')); 
                $pdf->save(public_path('assets/files/' . $name_file . ".pdf" ));         
            }

            $newDococumento = Documento::create([
                'user_id' => JWTAuth::user()->id,
                'path' => "" . URL::to('assets/files') . "/" . $name_file . ".". $data["type"] . "",
                'type' =>  $data["type"]
            ]);
            $user__ = JWTAuth::user();
            
            Log::create([
                "user_id" => $user__->id,
                "log_message" => "O usuario " . $user__->name . " correspondente ao ID " . $user__->id . " gerou um documento no dia " . $newDococumento->created_at->format('d/m/Y') . " as " . $newDococumento->created_at->format('H:i:s') . ". Formato do arquivo gerado: " . $data['type'] . "."
            ]);


            $response = [
                "success" => true,
                "log" => true,
                "message" => "Arquivo gerado com sucesso!",
                "data" => [
                    "uri" => $newDococumento->path
                ]
            ];
        });

        return $response;
    }



    public function meusDocs(){
        return Response::json([
            "success" => true,
            "log" => false,
            "message" => "Documentos pesquisados com sucesso!",
            "data" => [
                "documentos" => Documento::where("user_id", JWTAuth::user()->id)->get()
            ]
        ], 200); 
    }    

}
