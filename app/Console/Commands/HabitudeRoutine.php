<?php

namespace App\Console\Commands;

use App\Models\habit_possede;
use App\Models\Habitude;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class HabitudeRoutine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'habitude-routine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dayName = ucfirst(Carbon::now()->locale('fr')->dayName);

        $d = new ConsoleOutput();

        $daysOfWeek = [
            'Lundi' => 1,
            'Mardi' => 2,
            'Mercredi' => 3,
            'Jeudi' => 4,
            'Vendredi' => 5,
            'Samedi' => 6,
            'Dimanche' => 7,
        ];

        $id_day = $daysOfWeek[$dayName];

        $habits_list = habit_possede::where("day_id",$id_day)->get();
        foreach ($habits_list as $habit){
            $habitude = Habitude::find($habit->habit_id);
            $task = Task::find($habitude->task_id);

            $aujourdHui = Carbon::today();

            $datePersonnalisee = $aujourdHui->setTimeFromTimeString($habit->stop);
            $task->due_date = $datePersonnalisee;
            $task->is_finish = 0;
            $task->save();
        }
        $d->writeln($habits_list);


    }
}
