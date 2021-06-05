<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * método de resposta quando for sucesso.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResponse($resultado, $mensagem)
    {
        $response = [
            'sucesso' => true,
            'data'    => $resultado,
            'mensagem' => $mensagem,
        ];

        return response()->json($response, 200);
    }

    /**
     * método de resposta quando for erro.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendError($erro, $mensagensErro = [], $codigo = 401)
    {
        $response = [
            'sucesso' => false,
            'mensagem' => $erro,
        ];

        if(!empty($mensagensErro)){
            $response['data'] = $mensagensErro;
        }

        return response()->json($response, $codigo);
    }

    public function sendErrorEmail($erro, $mensagensErro = [], $codigo = 401)
    {
        $response = [
            'sucesso' => false,
            'mensagem' => 'Os dados enviados estão inválidos',
            'erros' => ['empresa' => ['Este email já esta em uso nesta empresa']]
        ];

        if(!empty($mensagensErro)){
            $response['data'] = $mensagensErro;
        }

        return response()->json($response, $codigo);
    }
}