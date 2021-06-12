<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //

    protected $fillable = ['user_id','log_message'];
    protected $table = 'logs';


    public function usuario(){
        return $this->belongsTo(User::class);
    }

}
