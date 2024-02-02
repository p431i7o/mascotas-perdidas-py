<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('from_user_id')->unsigned()->comment('Usuario remitente ');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->bigInteger('to_user_id')->unsigned()->nullable()->comment('Usuario destinatario');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->string('message',500)->comment('Mensaje');
            $table->enum('status',['Sent','Read'])->comment('Estados del mensaje');
            $table->dateTime('read_at')->comment('Fecha Hora de lectura');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
