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
            $table->bigInteger('parent_id')->unsigned()->nullable()->comment('Mensaje al que responde');
            $table->foreign('parent_id')->references('id')->on('messages');
            $table->bigInteger('from_user_id')->unsigned()->comment('Usuario remitente ');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->bigInteger('to_user_id')->unsigned()->nullable()->comment('Usuario destinatario');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->string('message',1500)->comment('Mensaje');
            $table->enum('status',['Sent','Read'])->comment('Estados del mensaje');
            $table->dateTime('read_at')->nullable()->comment('Fecha Hora de lectura');

            $table->bigInteger('report_id')->unsigned()->comment('Referencia a reporte');
            $table->foreign('report_id')->references('id')->on('reports');

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
