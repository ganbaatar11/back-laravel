<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Services\MainCommandService;

class CommandStatus extends Command
{
    protected $signature = 'crsh:status';

    protected $description = 'Schedule status';

    public function handle ()
    {
        MainCommandService::statusUpcomingToLive();
        MainCommandService::inProgressMatches();
        MainCommandService::statusLiveToCompleted();
    }
}