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
                'nivel_id' => $nivel_admin->id,
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

                    if(!is_null($token)){
                        $newUser->token = $token;
                        $newUser->save();
                       
                        $response = [
                            "success" => true,
                            "log" => true,
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
       
        // $date = $this->getDate();
        // if($user->admin == 1){
        //     DB::transaction(function() use($user, $date){
        //         $data_log = [
        //             "participante_id" => $user->id,
        //             "admin" => $user->nome,
        //             "email" => $user->email,
        //             "tabela" => "false",
        //             "ciente" => 1,
        //             "tipo" => 'LOGIN',
        //             "autenticado" => 1,
        //             "descricao" => "O usuario " . $user->nome . ' logou na plataforma no dia ' . $date['day'] . ' no horario: ' . $date['hora'] . ";"
        //         ];
        //         Log::create($data_log);
        //     });
        // }

        return response()->json([
            "success" => true,
            "log" => false,
            "message" => "Usuario logado com sucesso!",
            "data" => [
                "user" => $user,
                "token" => $token
            ]
        ], 200);
    }



    public function verificaUsuario(Request $request)
    {

        $participanteAutenticado = JWTAuth::parseToken()->authenticate();

        $participante = [
            'foto' => $participanteAutenticado->admin == 1 ? $participanteAutenticado->path : $participanteAutenticado->foto_perfil,
            'id' => $participanteAutenticado->id,
            'nome' => Str::limit($participanteAutenticado->nome, 16),
            'email' => $participanteAutenticado->email,
            'admin' => $participanteAutenticado->admin,
            'created_at' => $participanteAutenticado->created_at,
            'updated_at' => $participanteAutenticado->updated_at
        ];

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Usuario autenticado',
            'usuario' => $participante
        ], 201);
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
