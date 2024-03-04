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
        Schema::create('report_denounces', function (Blueprint $table) {

            $table->bigInteger('report_id')->unsigned()->comment('Referencia a reporte');
            $table->foreign('report_id')->references('id')->on('reports');


            $table->bigInteger('user_id')->unsigned()->comment('Usuario que denuncia ');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('comment',500);

            $table->timestamps();
            $table->softDeletes();

            $table->primary(['report_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_denounces');
    }
};
