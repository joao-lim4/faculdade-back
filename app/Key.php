<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $fillable = ['user_id','key','ativa'];
    protected $table = 'keys_users';


    public function usuario(){
        return $this->belongsTo(User::class);
    }
}
