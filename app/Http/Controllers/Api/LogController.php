<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use App\Log;
use Tymon\JWTAuth\Facades\JWTAuth;


class LogController extends Controller
{
 
    public function index(Request $request){
        
        if(JWTAuth::user()->nivel_id !== 1){
            return Response::json([
                "error" => true,
                "log" => false,
                "message" => "Sem autorização para continuar!",
            ], 401);
        }

        $data = $request->all();
        $logs = [];

        $where = function($q) use($data){

            if(isset($data["id"]) && $data["id"]){
                $q->where("id", $data["id"]);
            }
            
            if(isset($data["user_id"]) && $data["user_id"]){
                $q->where("user_id", $data["user_id"]);
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

        $logs = Log::where($where)->orderBy("id", "desc")->limit(30)->get();


        return Response::json([
            "success" => true,
            "log" => false,
            "message" => "Log pesuqisado com sucesso!",
            "data" => [
                "logs" => $logs
            ]
        ],200);
    }


}
