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
        Schema::create('champs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('formulaire_id');
            $table->unsignedBigInteger('type_id');
            $table->string('label');
            $table->integer('poids')->default(0);
            $table->boolean('required');
            $table->index(["formulaire_id"], 'fk_formulaire_champs_idx');
            $table->foreign('formulaire_id', 'fk_formulaire_champs_idx')
                ->references('id')->on('formulaires')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->index(["type_id"], 'fk_type_champ_idx');
            $table->foreign('type_id', 'fk_type_champ_idx')
                ->references('id')->on('champs_types')
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
        Schema::dropIfExists('champs');
    }
};
