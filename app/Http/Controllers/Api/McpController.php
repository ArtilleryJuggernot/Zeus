<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class McpController extends Controller
{
    public function getTasks()
    {
        // Récupérer toutes les tâches pour l'utilisateur connecté
        $tasks = Task::where('owner_id', Auth::id())->get();
        return response()->json($tasks);
    }

    public function getTaskById($id)
    {
        // Récupérer une tâche spécifique par son ID
        $task = Task::where('owner_id', Auth::id())->findOrFail($id);
        return response()->json($task);
    }

    public function updateTask(Request $request, $id)
    {
        // Mettre à jour une tâche
        $task = Task::where('owner_id', Auth::id())->findOrFail($id);
        $task->task_name = $request->input('task_name');
        $task->due_date = $request->input('due_date');
        $task->priority = $request->input('priority');
        $task->save();

        return response()->json($task);
    }

    public function deleteTask($id)
    {
        // Supprimer une tâche
        $task = Task::where('owner_id', Auth::id())->findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Tâche supprimée avec succès']);
    }

    public function createResource(Request $request)
    {
        // Exemple de création d'une ressource (note, task, etc.)
        // Cela pourrait être adapté selon le type de ressource
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:note,task,project',
            'priority' => 'nullable|string|in:low,medium,high',
        ]);

        $resource = null;

        if ($data['type'] === 'task') {
            $resource = new Task();
            $resource->task_name = $data['name'];
            $resource->owner_id = Auth::id();
            $resource->priority = $data['priority'] ?? 'medium';
            $resource->save();
        }

        // Ajouter d'autres types de ressources comme notes, projets, etc.

        return response()->json($resource, 201);
    }
}
