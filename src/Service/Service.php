<?php


namespace Fearless\ThinkTool\Service;

use app\command\Install;
use Fearless\ThinkTool\Command\DevAndLogCommand;
use Fearless\ThinkTool\Command\MakeController;
use Fearless\ThinkTool\Command\TestCommand;
use Fearless\ThinkTool\Command\UpdateMenu;
use think\facade\Console;

class Service extends \think\Service
{
    public function boot()
    {
        $commands = [TestCommand::class,MakeController::class,UpdateMenu::class,DevAndLogCommand::class,'hw:cover'=>DevAndLogCommand::class];
        if (!isset(Console::all()['hw:addons'])){
            $commands[] = Install::class;
        }
        $this->commands($commands);
    }
}
