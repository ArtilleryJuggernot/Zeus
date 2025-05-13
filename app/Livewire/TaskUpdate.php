<?php

namespace App\Livewire;

use App\Models\insideprojet;
use App\Models\task_priorities;
use Illuminate\Support\Facades\Auth;
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
    public $task;
    public $allCategories = [];
    public $categorySearch = '';

    protected $rules = [
        'taskName' => 'required|string|max:255',
        'dueDate' => 'nullable|date',
        'priority' => 'required|string|in:Urgence,Grande priorité,Prioritaire',
    ];

    protected $listeners = [
        'openEditModal' => 'openEditModal',
    ];

    public function mount($taskId, $taskName = null, $dueDate = null, $is_finish = null, $priority = null, $allCategories = [])
    {
        $this->taskId = $taskId;
        $this->taskName = $taskName;
        $this->dueDate = $dueDate;
        $this->is_finish = $is_finish;
        $this->priority = $priority;
        $this->allCategories = $allCategories;
        $this->task = Task::find($taskId);
        // Si certains paramètres ne sont pas fournis, on complète depuis la base
        if ($this->task) {
            if ($this->taskName === null) $this->taskName = $this->task->task_name;
            if ($this->dueDate === null) $this->dueDate = $this->task->due_date;
            if ($this->is_finish === null) $this->is_finish = $this->task->is_finish;
            if ($this->priority === null && task_priorities::where('task_id', $this->taskId)->first())
                $this->priority = task_priorities::where('task_id', $this->taskId)->first()->priority;
        }
    }

    public function updateTask()
    {

        $task = Task::find($this->taskId);
        if ($task) {
            $task->task_name = $this->taskName;

            $task->due_date = $this->dueDate == "" ? null : $this->dueDate;
            $task->is_finish = $this->is_finish;
            $task->save();

            // Mettre à jour la priorité
            if($this->priority != "None"){
                task_priorities::updateOrCreate(
                ['task_id' => $this->taskId],
                ['priority' => $this->priority],
                    ['user_id' => $task->owner_id]
            );
            }
            elseif ($this->priority == "None") {
                task_priorities::where([
                    ["task_id", "=", $this->taskId],
                    ["user_id", "=", Auth::user()->id],

                ])->delete();
            }

            session()->flash('success', 'Tâche mise à jour avec succès!');
            $this->isEditing = false; // Fermer l'éditeur après mise à jour
        }
    }


    public function updateTaskStatus($taskId, $status)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->is_finish = $status;
            $task->save();

            // Mettre à jour la propriété locale
            $this->is_finish = $status;
            session()->flash('success', 'Le statut de la tâche a été mis à jour avec succès!');
        }
    }


    public function deleteTask($taskId)
    {
        // Récupérer la tâche
        $task = Task::find($taskId);
        if ($task) {
            // Supprimer la tâche
            // Supprimer la priorité associée
            task_priorities::where('task_id', $taskId)->delete();
            insideprojet::where("task_id", $taskId)->delete();

            
            $task->delete();


            session()->flash('success', 'Tâche supprimée avec succès!');
        }
    }

    public function openEditModal($taskId)
    {
        $this->mount($taskId);
        $this->isEditing = true;
        $this->dispatch('lock-scroll');
    }

    public function closeEditModal()
    {
        $this->isEditing = false;
        $this->dispatch('unlock-scroll');
    }

    public function render()
    {
        return view('livewire.task-update');
    }




}
