<?php


namespace Fearless\ThinkTool\Command;

use think\console\Input;
use think\console\Output;
use think\facade\App;
use think\facade\Console;

class InitCommand extends \think\console\Command
{
    public function configure()
    {
        $this->setName('hw:init')
            ->setDescription('test');
    }

    public function execute(Input $input, Output $output)
    {
        Console::call('migrate:run');
        Console::call('seed:run');
        Console::call('hw:cover');
        $output->writeln('初始化完成');
    }
}
