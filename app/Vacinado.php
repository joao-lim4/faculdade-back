<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacinado extends Model
{
    protected $fillable = [
        'nome',
        'idade', 
        'sexo', 
        'cpf', 
        'path',
        'pais', 
        'assintomatico', 
        'infectado', 
        'bebida',
        'email',
        'contato',
        'user_id'
    ];

    protected $table = 'vacinados';

    public function usuario(){
        return $this->belongsTo(User::class);
    }

}
