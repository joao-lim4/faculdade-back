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
        'vacinado',
        'pais', 
        'assintomatico', 
        'infectado', 
        'bebida',
        'email',
        'contato',
        'turma',
        'curso',
        'turno',
        'user_id'
    ];

    protected $table = 'vacinados';

    public function usuario(){
        return $this->belongsTo(User::class);
    }

}
