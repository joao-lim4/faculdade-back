<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use http\Exception;
use App\Log;
use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Nivel;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{

    private function createLog($auth, $message = null, $registro = null, $table = null, $type = null)
    {

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
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
            [
                'login',
                'registrar',
                'checkAuth',
            ]
        ]);
    }

    private function valideteRegister (string $key, string $email="", bool $request=false){
        if($request){
            $user = User::where('email', $email)->first();
            if(!$user instanceof User){
                return true;
            }else{
                return false;
            }
        }else{
            return $key == env("KEY");
        }
    }

    //o registro e feito somente pelo ADM  dentro da aplicação
    public function registrar(AuthRequest $request)
    {
        $input = $request->all();
        $nivel_admin = Nivel::where("nome", "Admin")->first();

        if(!$this->valideteRegister($request->key)){
            return response()->json([
                "erro" => true,
                "log" => true,
                "message" => "Sem autorizacao para executar essa tarefa!"
            ], 401);
        }

        if($request->hasFile('path')){
            $file = $request->file('path');
            $ImgName = md5(uniqid(time()));
            $file->move(public_path('/assets/users'), $ImgName . '.' . $file->getClientOriginalExtension());
        
        
            $data = array(
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
                'nivel_id' => isset($input["nivel_id"]) && $input["nivel_id"] ? $input["nivel_id"] : $nivel_admin->id,
                'path' => 'http://127.0.0.1:8000/assets/users/' . $ImgName . '.' . $file->getClientOriginalExtension(),
            );
            

            if(!$this->valideteRegister("", $request->email, true)){
                return response()->json([
                        "status" => "Error",
                        "message" => "Esse usuario ja está cadastrado no sistema"
                ], 200);
            }else{
                DB::transaction(function() use ($data,$request, &$response){
                    $newUser = User::create($data);

                    $credentials = $request->only('email', 'password');
                    $token = JWTAuth::attempt($credentials);

                    $log = $this->createLog(JWTAuth::user(), null, $newUser, 'users', 'Post');


                    if(!is_null($token)){
                        $newUser->token = $token;
                        $newUser->save();
                       
                        $response = [
                            "success" => true,
                            "log" => $log,
                            "message" => "Usuario criado com sucesso!",
                            "data" => [
                                "user" => $newUser,
                                "token" => $token
                            ]
                        ];
                    }
                });
            }

            return response()->json($response, 200);

        }else{
            return response()->json([
                "error" => true,
                "log" => false,
                "message" => "É preciso enviar uma foto do usuario!"
            ], 200);
        }
    }

    private function getDate(){
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('d/m/y h:i:s', time());
        $ex = explode(" ", $date);
        return [
            "day" =>  $ex[0],
            "hora" =>  $ex[1]
        ];
    }

    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $token = JWTAuth::attempt($credentials);

            if(is_null(Auth::user())){
                return response()->json(["error" => "Usuario ou senha invalidos"], 202);
            }

        } catch (JWTException $e) {
            //return $e->getMessage();
            return response()->json(['error' => 'não foi possível gerar o token'], 500);
        }


        //busca o ultimo token valido gerado para o usuario
        $user = User::with(["nivel"])->where("id", JWTAuth::user()->id)->first();

        //invalida o token
        if ($user->token) {

            JWTAuth::setToken($user->token)->refresh();
        }

        //salva novo token no banco
        $user->token = $token;
        $user->save();
       
        $date = $this->getDate();
        
        DB::transaction(function() use($user, $date){
            Log::create([
                "user_id" => $user->id,
                "log_message" => "O usuario " . $user->nome . ' logou na plataforma no dia ' . $date['day'] . ' no horario: ' . $date['hora'] . ";"
            ]);
        });
        

        return response()->json([
            "success" => true,
            "log" => true,
            "message" => "Usuario logado com sucesso!",
            "data" => [
                "user" => $user,
                "token" => $token
            ]
        ], 200);
    }



    public function verificaUsuario(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if($user instanceof User){
            return response()->json([
                'sucesso' => true,
                'mensagem' => 'Usuario autenticado',
                'usuario' => $user
            ], 200);
        }else{
            return response()->json([
                'error' => true,
                'mensagem' => 'Usuario não autenticado',
            ], 401);
        }

    }

    public function atualizarToken()
    {
        if (!$token = JWTAuth::getToken()) {
            return response()->json(['error' => 'token-not-send'], 401);
        }
        try {
            $token = JWTAuth::refresh();
        } catch (TokenInvalidException $e) {
        }

        $participante = Auth::user();

        return response()->json(compact('token', 'usuario'));
    }

    public function checkAuth(){
        if(is_null(Auth::user())){
            return 'false';
        }
    }

    public function testeLocale()
    {
        return $this->app->getLocale();
    }
}
