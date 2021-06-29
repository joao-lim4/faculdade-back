<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Key;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Log;
use App\Nivel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class KeyController extends Controller
{   

    private function gerarKey():string {
        $s = strtoupper(md5(uniqid(rand(),true))); 
        $guidText = 
            substr($s,0,4) . '-' . 
            substr($s,8,4) . '-' . 
            substr($s,12,4). '-' . 
            substr($s,16,4). '-' . 
            substr($s,20,4); 
        return $guidText;
    }

    public function generateKey(Request $request){

        $key = $this->gerarKey();

        $nivel_admin = Nivel::where("nome", "Admin")->first();

        $user = User::find(JWTAuth::user()->id);

        if(!$user instanceof User){
            return Response::json([
                "error" => true,
                "log" => false,
                "message" => "Algo de errado aconteceu, tente novamente mais tarde!"
            ], 400);
        }else{
            if($user->nivel_id > $nivel_admin->id){
                return Response::json([
                    "error" => true,
                    "log" => false,
                    "message" => "Sem permissões para continuar!"
                ], 400); 
            }
        }

        //select * from keys where user_id=1 and where ativa=1 limit 1;
        $keyAt = Key::where("user_id", $user->id)
                    ->where('ativa', 1)
                    ->query();

        if($keyAt instanceof Key){
            return Response::json([
                "success" => true,
                "log" => false,
                "message" => "Key gerada com sucesso",
                "data" => [
                    "key" => $keyAt->key
                ]
            ], 200);
        }


        DB::transaction(function() use($key, $user, &$resposne) {
            Key::create([
                "user_id" => $user->id,
                "key" => $key
            ]);
 
            $resposne = [
                "success" => true,
                "log" => false,
                "message" => "Key gerada com sucesso",
                "data" => [
                    "key" => $key
                ]
            ];
        });

        return Response::json($resposne, 200);
    }
}
