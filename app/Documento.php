<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = ['user_id','path','type'];
    protected $table = 'documentos';


    public function usuario(){
        return $this->belongsTo(User::class);
    }
}
