<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
            $table->string('email',256)->nullable();
            $table->string('uuid',36)->nullable();
        });
        \App\Models\Report::where('uuid',null)
            ->orderBy('id','asc')
            ->each(function ($report) {
                $report->uuid = \Str::uuid();
                $report->save();
        });

    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['email', 'uuid']);
            $table->bigInteger('user_id')->unsigned()->nullable(false)->change();
        });
    }
};
