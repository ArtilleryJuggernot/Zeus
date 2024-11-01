<?php

namespace App\Livewire;

use App\Models\task_priorities;
use Livewire\Component;
use App\Models\Task;
use App\Models\TaskPriority;

class TaskUpdate extends Component
{
    public $taskId;
    public $taskName;
    public $dueDate;
    public $priority;
    public $is_finish;
    public $isEditing = false; // État de l'édition

    protected $rules = [
        'taskName' => 'required|string|max:255',
        'dueDate' => 'nullable|date',
        'priority' => 'required|string|in:Urgence,Grande priorité,Prioritaire',
    ];

    public function mount($taskId)
    {
        $this->task = Task::find($taskId);
        if ($this->task) {
            $this->taskId = $this->task->id;
            $this->taskName = $this->task->task_name;
            $this->dueDate = $this->task->due_date;
            $this->is_finish = $this->task->is_finish;

            // Récupérer la priorité de la tâche




            if(task_priorities::where("task_id",$this->taskId)->first())
                $this->priority = task_priorities::where('task_id', $this->taskId)->first()->priority;

        }
    }

    public function updateTask()
    {
        $this->validate();

        $task = Task::find($this->taskId);
        if ($task) {
            $task->task_name = $this->taskName;

            $task->due_date = $this->dueDate == "" ? null : $this->dueDate;
            $task->is_finish = $this->is_finish;
            $task->save();

            // Mettre à jour la priorité
            task_priorities::updateOrCreate(
                ['task_id' => $this->taskId],
                ['priority' => $this->priority]
            );

            session()->flash('success', 'Tâche mise à jour avec succès!');
            $this->isEditing = false; // Fermer l'éditeur après mise à jour
        }
    }

    public function render()
    {
        return view('livewire.task-update');
    }
}
