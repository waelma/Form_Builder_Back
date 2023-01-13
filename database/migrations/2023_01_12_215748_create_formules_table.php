<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('champ_id');
            $table->unsignedBigInteger('type_id');
            $table->string('reference');
            $table->integer('poids')->default(0);
            $table->index(['type_id'], 'fk_formule_type_idx');
            $table
                ->foreign('type_id', 'fk_formule_type_idx')
                ->references('id')
                ->on('formules_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->index(['champ_id'], 'fk_formule_champ_idx');
            $table
                ->foreign('champ_id', 'fk_formule_champ_idx')
                ->references('id')
                ->on('champs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('formules');
    }
};
