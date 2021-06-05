<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class VacinadosController extends Controller
{
    
    private function validateImage($request, bool $storage){
        if($request->hasFile('path')){
            $file = $request->file('path');
            $ImgName = md5(uniqid(time()));
            
            if($storage){
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
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            return var_dump($file);
            return Response::json([
                'error' => true,
                'log' => false,
                'message' => "É preciso enviar uma imagem para esse usuario!"
            ]);
        }else{

            return var_dump($file);

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
