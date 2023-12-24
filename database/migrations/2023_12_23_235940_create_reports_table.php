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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Nombre de la mascota');

            $table->bigInteger('animal_kind_id')->unsigned()->comment('Tipo de animal');
            $table->foreign('animal_kind_id')->references('id')->on('animal_kinds');

            $table->enum('type',['Found','Lost'])->comment('Tipo de reporte');
            $table->dateTime('date')->comment('Fecha hora del reporte');
            $table->string('description',1000)->nullable()->comment('Descripcion del reporte');
            $table->dateTime('expiration')->comment('Fecha de expiracion del posteo');
            $table->string('address',200)->comment('Direccin aprox de donde fue perdido/encontrado');
            $table->string('latitude',20)->comment('Latitud del reporte');
            $table->string('longitude',20)->comment('Longitud del reporte');
            $table->enum('status',['Active','Inactive']);

            $table->bigInteger('departamento_id')->unsigned()->nullable();
            $table->bigInteger('ciudad_id')->unsigned()->nullable();
            $table->bigInteger('distrito_id')->unsigned()->nullable();
            $table->bigInteger('barrio_id')->unsigned()->nullable();

            $table->foreign('departamento_id')->references('id')->on('departments');
            $table->foreign('ciudad_id')->references('id')->on('cities');
            $table->foreign('distrito_id')->references('id')->on('districts');
            $table->foreign('barrio_id')->references('id')->on('neighborhoods');
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
        Schema::dropIfExists('reports');
    }
};