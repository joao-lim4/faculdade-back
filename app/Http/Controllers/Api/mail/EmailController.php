<?php

namespace App\Http\Controllers\Api\mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Http\Controllers\Controller;
use App\Participante;
use Illuminate\Support\Facades\Response;

class EmailController extends Controller
{

    public function index(){
        return view('index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function passwordReset(Request $request){
        try{
            $user =  Participante::where('resete_password',$request->input('key'))->first();
            $hash =  Hash::make($request->input('password'));
            $user->password = $hash;
            $user->resete_password = null;
            $user->save();
            return response()->json(["status" => 'Senha alterada com sucesso!'], 200);
        }catch(Exception $ex){
            return $ex;
        }
    }


   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function webroute(Request $request){
        
        try{
            $user =  Participante::where('email',$request->input('email'))->first();
            if(gettype($user) === 'object'){
                $key = md5(uniqid(time()));
                $user->resete_password = $key;
                $user->save();
                Mail::send('teste', ["key"=>$key, "nome" => $user->nome], function($message){
                    $message->subject('Alteração de senah');
                    $message->to('cl4sh.of.cl4ans2016@gmail.com');
                });
                $response = [
                    "status" => 'Email enviado com sucesso',
                    "user" => [ 
                        "nome" => $user->nome,
                        "email" => $user->email
                    ],
                    "request" => [
                        'type' => "POST",
                        'status_code' => 200
                    ]
                ];
                return response()->json($response,200);
            }else{
                return response()->json(['status' => 'Email não encontrado'],202);
            }
        }catch(Exception $ex){
            return response()->json(['status' => 'error'], 400);
        } 
    }


    public function emailSun(Request $request){

        try{
            Mail::send('orca', ["nome" => $request->nome,'telefone' => $request->telefone , 'email' => $request->email], function($message) use($request){
                
                $message->subject('Proosta para orçamento');

                $files = $request->file('file');

                foreach($files as $file){
                    $message->attach($file->getRealPath(), [
                        'as'   => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType()
                    ]);
                }

                $message->to('contatosunvibesenergia@gmail.com');
            });

            return Response::json(['success' => true], 200);
        }catch(Exception $ex){
            return $ex;
        }
    
    }
}
