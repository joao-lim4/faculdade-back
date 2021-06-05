<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacinadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacinados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("nome", 255);
            $table->integer("idade");
            $table->string("sexo", 255);
            $table->string("cpf", 255);
            $table->longText("path", 255);
            $table->string("pais");
            $table->boolean("assintomatico")->default(0);
            $table->boolean("infectado")->default(0);
            $table->boolean("bebida")->default(0);
            $table->string("email", 255);
            $table->string("contato", 255);
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacinados');
    }
}
