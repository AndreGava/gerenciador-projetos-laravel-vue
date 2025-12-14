<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * POST /api/tasks: Cria uma nova tarefa.
     */
    public function store(Request $request): JsonResponse
    {
        // Validação: 'project_id' deve existir; 'difficulty' deve ser um dos valores válidos.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'difficulty' => 'required|in:baixa,média,alta', // Usando 'média' com acento
        ]);

        // O campo 'completed' será false por padrão, conforme definido na migration.
        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    /**
     * PATCH /api/tasks/{task}/toggle: Marcar uma tarefa como concluída ou não[cite: 22].
     */
    public function toggle(Task $task): JsonResponse
    {
        // Inverte o valor booleano do campo 'completed'
        $task->completed = !$task->completed;
        $task->save();

        // Retorna a tarefa atualizada
        return response()->json($task);
    }

    /**
     * DELETE /api/tasks/{task}: Excluir uma tarefa[cite: 23].
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        // Retorna 204 No Content para indicar sucesso na exclusão
        return response()->json(null, 204);
    }
}
