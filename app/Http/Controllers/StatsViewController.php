<?php

namespace App\Http\Controllers;

use App\Models\stats;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StatsViewController extends Controller
{
    public function ViewWeekly()
    {
        $user_id = Auth::user()->id;
        $monday = Carbon::now()->startOfWeek(); // 'Y-m-d H:i'
        $sunday = Carbon::now()->endOfWeek();
        $stats = $this->getStatsBetweenTwoDate(
            $user_id,
            $monday->format('Y-m-d'),
            $sunday->format('Y-m-d')
        );

        $statsOverall = ProfilController::getUserStats($user_id);

        $zeusStartDate = new Carbon('2024-01-01');
        $statsOverallGraph = $this->getStatsBetweenTwoDate($user_id,
            $zeusStartDate->format("Y-m-d"),
            Carbon::now()->addDay()->format("Y-m-d")
        );

      /*  $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("stats.stats",
            [
                "stats" => $stats,
                "statsOverall" => $statsOverall,
                "monday" => $monday,
                "sunday" => $sunday,
                "render" => true,
                "statsOverallGraph" => $statsOverallGraph
            ]);


        return $pdf->stream();
      */


        return view("stats.stats",
        [
           "stats" => $stats,
           "statsOverall" => $statsOverall,
           "monday" => $monday,
           "sunday" => $sunday,
            "render" => false,
            "statsOverallGraph" => $statsOverallGraph
        ]);

    }


    public function getStatsBetweenTwoDate($userId, $dt1, $dt2)
    {
        // Nombre de notes créées entre d1 et d2
        $notesCreated = stats::where('user_id', $userId)
            ->whereBetween('created_at', [$dt1, $dt2])
            ->where('action', '=', 'CREATE NOTE')
            ->get();

        // Nombre de dossiers créés entre d1 et d2
        $foldersCreated = stats::where('user_id', $userId)
            ->whereBetween('created_at', [$dt1, $dt2])
            ->where('action', '=', 'CREATE FOLDER')
            ->get();

        // Nombre de tâches créées entre d1 et d2
        $tasksCreated = stats::where('user_id', $userId)
            ->whereBetween('created_at', [$dt1, $dt2])
            ->where('action', '=', 'CREATE TASK')
            ->get();

        // Nombre de projets créés entre d1 et d2
        $projectsCreated = stats::where('user_id', $userId)
            ->whereBetween('created_at', [$dt1, $dt2])
            ->where('action', '=', 'CREATE PROJECT')
            ->get();

        // Nombre de catégories créées entre d1 et d2
        $categoriesCreated = stats::where('user_id', $userId)
            ->whereBetween('created_at', [$dt1, $dt2])
            ->where('action', '=', 'CREATE CATEGORY')
            ->get();


        return [
            'notesCreated' => $notesCreated,
            'foldersCreated' => $foldersCreated,
            'tasksCreated' => $tasksCreated,
            'projectsCreated' => $projectsCreated,
            'categoriesCreated' => $categoriesCreated,
        ];
    }




}
