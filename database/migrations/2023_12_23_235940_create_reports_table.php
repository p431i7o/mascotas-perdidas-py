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
            $table->string('address',200)->comment('Direccion aprox de donde fue perdido/encontrado');
            $table->string('latitude',20)->comment('Latitud del reporte');
            $table->string('longitude',20)->comment('Longitud del reporte');
            $table->enum('status',['Pending','Active','Inactive','Rejected','Resolved'])->comment('Pending: Pendiente de moderacion, Active: En busqueda, Inactive: Dado de baja por el user, Rejected: Moderacion determino no valido, Resolved: volvio a casa');

            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->bigInteger('neighborhood_id')->unsigned()->nullable();

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('neighborhood_id')->references('id')->on('neighborhoods');

            $table->bigInteger('user_id')->unsigned()->comment('Usuario que reporta');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('approved_by')->unsigned()->nullable()->comment('Usuario que aprueba');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->dateTime('approved_date')->nullable();
            $table->string('observations',500)->nullable()->comment('Observaciones internas, por ej Razon por la que se rechaza');
            $table->json('attachments')->comment('JSON conteniendo informacion de los adjuntos subidos');

            $table->integer('views')->default(0)->comment('Cantidad de visualizaciones');
            $table->integer('renewed')->default(0)->comment('Veces que se renovó');
            $table->json('log')->comment('Cambios que sufre el registro');
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
