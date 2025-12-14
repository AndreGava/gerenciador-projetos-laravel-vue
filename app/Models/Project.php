<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Adiciona o campo virtual 'weighted_progress' à serialização do modelo.
     */
    protected $appends = ['weighted_progress'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Accessor para calcular o progresso ponderado do projeto.
     * O nome do método deve ser get{CamelCaseAttributeName}Attribute.
     * O atributo estará disponível em $project->weighted_progress.
     */
    public function getWeightedProgressAttribute(): float
    {
        // Garante que a relação 'tasks' esteja carregada.
        // O método with('tasks') no controller é mais eficiente para coleções.
        $tasks = $this->relationLoaded('tasks') ? $this->tasks : $this->tasks()->get();

        if ($tasks->isEmpty()) {
            return 0.0;
        }

        $totalEffort = 0;
        $completedEffort = 0;

        foreach ($tasks as $task) {
            // O accessor 'effort_points' no modelo Task será chamado automaticamente.
            $effortPoints = $task->effort_points;
            $totalEffort += $effortPoints;

            if ($task->completed) {
                $completedEffort += $effortPoints;
            }
        }

        if ($totalEffort === 0) {
            return 0.0;
        }

        $progress = ($completedEffort / $totalEffort) * 100;

        return round($progress, 2);
    }
}
