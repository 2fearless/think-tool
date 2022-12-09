<?php


namespace Fearless\ThinkTool\Service;

use Fearless\ThinkTool\Command\DevAndLogCommand;
use Fearless\ThinkTool\Command\MakeController;
use Fearless\ThinkTool\Command\TestCommand;
use Fearless\ThinkTool\Command\UpdateMenu;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands([TestCommand::class,MakeController::class,UpdateMenu::class,DevAndLogCommand::class,'hw:cover'=>DevAndLogCommand::class]);
    }
}
