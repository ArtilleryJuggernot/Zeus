<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskUpdate extends Component
{
    public $taskId;
    public $taskName;
    public $task;
    public $dueDate;
    public $priority;
    public $is_finish;
    public $isEditing = false; // État de l'édition

    protected $rules = [
        'taskName' => 'required|string|max:255',
        'dueDate' => 'nullable|date',
        'priority' => 'required|string|in:low,medium,high',
    ];

    public function mount($taskId)
    {
        $this->task = Task::find($taskId);
        if ($this->task) {
            $this->taskId = $this->task->id;
            $this->taskName = $this->task->task_name;
            $this->dueDate = $this->task->due_date;
            //$this->priority = $this->task->priority;
            $this->is_finish = $this->task->is_finish;
        }
    }

    public function updateTask()
    {
        $this->validate();

        $task = Task::find($this->taskId);
        if ($task) {
            $task->task_name = $this->taskName;
            $task->due_date = $this->dueDate;
            $task->priority = $this->priority;
            $task->is_finish = $this->is_finish;
            $task->save();

            session()->flash('success', 'Tâche mise à jour avec succès!');
            $this->isEditing = false; // Fermer l'éditeur après mise à jour
            $this->emit('taskUpdated'); // Émettre un événement pour rafraîchir les données
        }
    }

    public function render()
    {
        return view('livewire.task-update');
    }
}
