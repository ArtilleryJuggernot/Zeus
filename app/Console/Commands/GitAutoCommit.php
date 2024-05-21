<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitAutoCommit extends Command
{
    protected $signature = 'git:auto-commit';
    protected $description = 'Automatically add, commit, and push changes to the repository';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $commands = [
            'cd ./storage/app/files/',
            'git config user.email "zeusartilleryuno@gmail.com"',
            'git config user.name "Zeus autosave" ',
            'git add .',
            'git commit -m "Scheduled commit"',
            'git push'
        ];

        foreach ($commands as $command) {
            $process = Process::fromShellCommandline($command);

            try {
                $process->mustRun();
                $this->info($process->getOutput());
            } catch (ProcessFailedException $exception) {
                $this->error($exception->getMessage());
                Log::error('Git command failed', ['command' => $command, 'error' => $exception->getMessage()]);
                return 1; // Return a non-zero code to indicate failure
            }
        }

        return 0; // Return zero to indicate success
    }
}
