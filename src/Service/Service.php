<?php


namespace Fearless\ThinkTool\Service;

use Fearless\ThinkTool\Command\MakeController;
use Fearless\ThinkTool\Command\TestCommand;
use Fearless\ThinkTool\Command\UpdateMenu;
use thans\jwt\command\SecretCommand;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands([TestCommand::class,MakeController::class,UpdateMenu::class]);
    }
}
