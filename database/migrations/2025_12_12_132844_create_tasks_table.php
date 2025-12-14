<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // adicione o título da tarefa
            $table->boolean('completed')->default(false); //Deafult false para indicar que a tarefa não foi concluída

            // Coluna de Dificuldade
            // Usei enum para garantir que sejam os valores permitidos
            $table->enum('difficulty', ['baixa', 'média', 'alta']);

            //Chave estrangeira para o Project
            $table->foreignId('project_id')
                ->constrained() // cria a constraint para a tabela "projects"
                ->onDelete('cascade'); // Se um projeto for deletado, suas tarefas também serão



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
