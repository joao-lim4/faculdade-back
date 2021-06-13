<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Vacinado;
use App\Log;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class VacinadosController extends Controller
{
    
    private function validateImage($request, bool $storage){
        if($request->hasFile('path')){
            if($storage){
                $file = $request->file('path');
                $ImgName = md5(uniqid(time()));
                $file->move(public_path('/assets/vacinados'), $ImgName . '.' . $file->getClientOriginalExtension());    
                
                return [
                    "success" => true,
                    "path" => 'http://127.0.0.1:8000/assets/vacinados/' . $ImgName . '.' . $file->getClientOriginalExtension()
                ];
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    

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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $data = $request->all();

        $where = function($q) use($data){
            if(isset($data['user_id']) && $data['user_id']){
                $q->where('user_id', $data['user_id']);
            }

            if(isset($data['pais']) && $data['pais']){
                $q->where('pais', $data['pais']);
            }

            if(isset($data['vacinado']) && $data['vacinado']){
                $q->where('vacinado', $data['vacinado']);
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

        return Response::json([
            'success' => true,
            'log' => false,
            'message' => 'Conteudo pesquisado com sucesso!',
            'data' => [
                'vacinados' => Vacinado::with(["usuario"])->where($where)->get()
            ]
        ], 200);


    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->all();

        if(!$file = $this->validateImage($request, true)){
            return Response::json([
                'error' => true,
                'log' => false,
                'message' => "É preciso enviar uma imagem para esse usuario!"
            ], 400);
        }else{

            DB::transaction(function() use($data,$file, &$response){
                
                $new_vacinado = Vacinado::create([
                    'nome' => $data["nome"],
                    'idade' => $data["idade"], 
                    'sexo' => $data["sexo"], 
                    'cpf' => $data["cpf"], 
                    'path' => $file["path"], 
                    'pais' => $data["pais"],
                    'vacinado' => $data['vacinado'],
                    'assintomatico' => isset($data["assintomatico"]) ? $data["assintomatico"] : 0, 
                    'infectado' => isset($data["infectado"]) ? $data["infectado"] : 0, 
                    'bebida' => isset($data["bebida"]) ? $data["bebida"] : 0,
                    'email' => $data["email"],
                    'contato' => $data["contato"],
                    'user_id' => JWTAuth::user()->id
                ]);

                $log = $this->createLog(JWTAuth::user(), null, $new_vacinado, 'vacinados', 'Post');

                $response = [
                    'success' => true,
                    'log' => $log,
                    'message' => 'Vacinado cadastrado com sucesso!',
                    'data' => [
                        'vacinado' => $new_vacinado,
                    ]
                ];
            });

            return Response::json($response, 200);

        }

    }

    private function generateText(Vacinado $vacinado){
        $msg = "";

        if($vacinado->assintomatico == 1){
            $msg += "O aluno durante a pandemia foi infectado com o SARS COV 2 porém não apresentou sinstomas. ";
        }else{
            if($vacinado->infectado == 1){
                $msg += "O aluno durante a pandemia foi infectado e nescessitou de cuidaddos medicos. ";
            }else{
                $msg += "O aluno durante a não tem provas de que foi infectado. ";
            }
        }

        if($vacinado->bebida == 1){
            $msg += "O aluno ingeriu bebidas alcolicas 14 antes ou depois de ter tomado a vacina. ";
        }else{
            $msg += "O aluno não ingeriu bebidas alcolicas 14 antes ou depois de ter tomado a vacina. ";
        }    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vacinado = Vacinado::find($id);

        if($vacinado instanceof Vacinado){

            $body_messages = "";

            if($vacinado->vacinado === 1){
                $body_messages = "O aluno(a) " . $vacinado->nome . " ja foi vacinado contra o COVID-19, com as informações armazenadas desse aluno é possivel afirmar que, " . $this->generateText($vacinado) . "";  
            }else{
                $body_messages = "Não foi possivel gerar um conteudo sobre esse aluno(a)";  

            }

            return Response::json([
                'success' => true,
                'log' => false,
                'message' => 'Vacinado cadastrado com sucesso!',
                'data' => [
                    'vacinado' => $vacinado,
                    'conteudo' => $body_messages
                ]
            ]);
        }else{
            return Response::json([
                'error' => true,
                'log' => false,
                'messagee' => 'O vacinado correspondente ao id ' . $id . ' não foi encontrado.'
            ], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $vacinado = Vacinado::find($id);

        $data = $request->all();

        if(!$vacinado instanceof Vacinado){
            return Response::json([
                'error' => true,
                'log' => false,
                'message' => "O registro correspondente ao id " . $id . " não foi encontrado, revise o seu id!"
            ], 400);
        }else{
            
            if($vacinado->user_id !== JWTAuth::user()->id){
                return Response::json([
                    'error' => true,
                    'log' => false,
                    'message' => 'Você não tem permissões para alterar esse registro' 
                ], 401);
            }


            if($file = $this->validateImage($request, true)){
                try {
                    File::delete(public_path('assets/vacinados') . '/' . explode("vacinados/", $vacinado->path)[1]);   
                } catch(Exception $e) {
                    
                    if(file_exists(public_path('assets/vacinados/') . explode("vacinados/", $file['path'])[1])){ 
                        unlink(public_path('assets/vacinados/') . explode("vacinados/", $file['path'])[1]);
                    }

                    return Response::json([
                        'error' => true,
                        'log' => false,
                        'message' => "Algo de errado aconteceu, entre em contato com os desenvolvedores!",
                        'data' => [
                            'error' => $e->get_message(),
                            'line' => $e->get_line()
                        ]
                    ], 400);
                }
            }   
            
            DB::transaction(function() use($data, $vacinado,$password, $file, &$response) {

                $vacinado->update([
                    'nome' => isset($data["nome"]) ? $data["nome"] : $vacinado->nome ,
                    'idade' => isset($data["idade"]) ? $data["idade"] : $vacinado->idade, 
                    'sexo' => isset($data["sexo"]) ? $data["sexo"] : $vacinado->sexo, 
                    'cpf' => isset($data["cpf"]) ? $data["cpf"] : $vacinado->cpf, 
                    'path' => isset($file["path"]) && $file ? $file["path"] : $vacinado->path,
                    'pais' => isset($data["pais"]) ? $data["pais"] : $vacinado->pais, 
                    'vacinado' => isset($data["vacinado"]) ? $data["vacinado"] : $vacinado->vacinado, 
                    'assintomatico' => isset($data["assintomatico"]) ? $data["assintomatico"] : $vacinado->assintomatico, 
                    'infectado' => isset($data["infectado"]) ? $data["infectado"] : $vacinado->infectado, 
                    'bebida' => isset($data["bebida"]) ? $data["bebida"] : $vacinado->bebida,
                    'email' => isset($data["email"]) ? $data["email"] : $vacinado->email,
                    'contato' => isset($data["contato"]) ? $data["contato"] : $vacinado->contato,
                ]);

                $log = $this->createLog(JWTAuth::user(), null, $vacinado, 'vacinados', 'Update');

                $response = [
                    'success' => true,
                    'log' => $log,
                    'message' => "Registro atualizado com sucesso!",
                    'data' => [
                        'vacinado' => $vacinado
                    ]
                ];

            });

            return Response::json($response, 200);

        }
    }
        

    
    public function destroy($id)
    {
        $vacinado = Vacinado::find($id);

        if(!$vacinado instanceof Vacinado){
            return Response::json([
                'error' => true,
                'log' => false,
                'message' => "O registro correspondente ao id " . $id . " não foi encontrado, revise a sua pesquisa!"
            ], 400);
        }else{
            
            if($vacinado->user_id !== JWTAuth::user()->id){
                return Response::json([
                    'error' => true,
                    'log' => false,
                    'message' => 'Você não tem permissões para apagar esse registro' 
                ], 401);
            }

            DB::transaction(function() use($vacinado, &$response){
                File::delete(public_path('assets/vacinados') . '/' . explode('vacinados/', $vacinado->path)[1]);
                Vacinado::destroy($vacinado->id);

                $log = $this->createLog(JWTAuth::user(), null, $vacinado, 'vacinados', 'Delete');


                $response = [
                    'success' => true,
                    'log' => $log,
                    'message' => "Registro apagado com sucesso!",
                ];

            });

            return Response::json($response, 200);

        }
    }
}
