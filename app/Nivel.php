<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $fillable = ['nome'];
    protected $table = 'niveis';


    public function usuarios(){
        return $this->hasMany(User::class);
    }
}
