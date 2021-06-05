<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //

    protected $fillable = ['participante_id','tabela','ciente', 'tipo','autenticado','admin', 'email', 'descricao'];
    protected $table = 'logs';

}
