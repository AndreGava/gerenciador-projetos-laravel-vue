<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * GET /api/projects: Lista todos os projetos.
     */
    public function index(): JsonResponse
    {
        $projects = Project::select('id', 'name')->get();

        return response()->json($projects);
    }

    /**
     * POST /api/projects: Cria um novo projeto.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    /**
     * GET /api/projects/{id}: Retorna o projeto com o campo calculado 'progress'.
     * CORREÇÃO CRUCIAL 1: Recebe $id (string) para contornar falha do Route Model Binding no teste.
     */
    public function show(string $id): JsonResponse
    {
        // NOVO: Faz a busca manual
        $project = Project::findOrFail($id);

        // Garante que as tarefas sejam carregadas
        $project->load('tasks');

        // Lógica do Cálculo do Progresso Ponderado
        $progress = $this->calculateWeightedProgress($project);

        // Retorna os dados do projeto junto com o campo calculado 'progress'
        return response()->json([
            'id' => $project->id,
            'name' => $project->name,
            'tasks' => $project->tasks,
            'progress' => $progress,
        ]);
    }

    /**
     * Lógica do Cálculo do Progresso Ponderado.
     */
    private function calculateWeightedProgress(Project $project): float
    {
        // 1. Caso Base: Se o projeto não tiver tarefas, o progresso é 0%
        if ($project->tasks->isEmpty()) {
            return 0.0;
        }

        $totalEffort = 0;
        $completedEffort = 0;

        // 2. Itera sobre todas as tarefas do projeto
        foreach ($project->tasks as $task) {

            // CORREÇÃO CRUCIAL 2: Acesse o Accessor (getEffortPointsAttribute)
            // como uma propriedade dinâmica (effort_points) para funcionar no teste.
            // Se o seu método se chama getXAttribute(), o acesso é $task->x
            $effortPoints = $task->effort_points;

            // Soma o esforço total
            $totalEffort += $effortPoints;

            // Se a tarefa estiver concluída, soma seu esforço ao total concluído
            if ($task->completed) {
                $completedEffort += $effortPoints;
            }
        }

        // 3. Evita divisão por zero
        if ($totalEffort === 0) {
            return 0.0;
        }

        // 4. Calcula a porcentagem do progresso.
        $progress = ($completedEffort / $totalEffort) * 100;

        // Retorna o progresso arredondado em duas casas decimais
        return round($progress, 2);
    }
}
