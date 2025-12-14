<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'completed' => false,
            // Escolhe aleatoriamente uma dificuldade válida
            'difficulty' => fake()->randomElement(['baixa', 'média', 'alta']),
            // Cria um projeto automaticamente se nenhum for passado
            'project_id' => Project::factory(),
        ];
    }
}
