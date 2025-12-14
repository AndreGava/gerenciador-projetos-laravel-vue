<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectProgressTest extends TestCase
{
    // Limpa o banco de dados a cada teste para garantir isolamento
    use RefreshDatabase;

    /**
     * Teste: Se um projeto não tiver tarefas, seu progresso é 0%.
     */
    public function test_project_without_tasks_has_zero_progress(): void
    {
        // 1. Cria um projeto sem tarefas
        $project = Project::factory()->create();

        // 2. Chama a API
        $response = $this->getJson("/api/projects/{$project->id}");

        // 3. Verifica se o progresso é 0
        $response->assertStatus(200)
            ->assertJson([
                'id' => $project->id,
                'progress' => 0,
            ]);
    }

    /**
     * Teste: Cálculo de progresso ponderado complexo.
     * Cenário:
     * - 1 Tarefa BAIXA (1 ponto) -> Concluída
     * - 1 Tarefa MÉDIA (4 pontos) -> Concluída
     * - 1 Tarefa ALTA (12 pontos) -> Pendente
     *
     * Total Pontos: 1 + 4 + 12 = 17
     * Pontos Concluídos: 1 + 4 = 5
     * Cálculo Esperado: (5 / 17) * 100 = 29.4117... -> Arredondado: 29.41
     */
    public function test_calculates_weighted_progress_correctly(): void
    {
        // 1. Cria o Projeto
        $project = Project::factory()->create();

        // 2. Cria as Tarefas com os estados específicos
        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'baixa', // 1 ponto
            'completed' => true,
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'média', // 4 pontos
            'completed' => true,
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'alta', // 12 pontos
            'completed' => false,
        ]);

        // 3. Chama a API
        $response = $this->getJson("/api/projects/{$project->id}");

        // 4. Verifica o resultado exato (29.41)
        $response->assertStatus(200)
            ->assertJsonPath('progress', 29.41);
    }

    /**
     * Teste: Verificar se alternar uma tarefa (toggle) atualiza o progresso no endpoint do projeto.
     */
    public function test_completing_a_task_updates_project_progress(): void
    {
        $project = Project::factory()->create();

        // Cria uma tarefa de alta dificuldade (12 pontos) não concluída
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => 'alta',
            'completed' => false,
        ]);

        // Verifica progresso inicial (0%)
        $this->getJson("/api/projects/{$project->id}")
            ->assertJsonPath('progress', 0);

        // Marca a tarefa como concluída via API
        $this->patchJson("/api/tasks/{$task->id}/toggle");

        // Verifica novo progresso (deve ser 100% pois é a única tarefa)
        $this->getJson("/api/projects/{$project->id}")
            ->assertJsonPath('progress', 100);
    }
}
