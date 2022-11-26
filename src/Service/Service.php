<?php


namespace Fearless\ThinkTool\Service;

use Fearless\ThinkTool\Command\TestCommand;
use thans\jwt\command\SecretCommand;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands(TestCommand::class);
    }
}
