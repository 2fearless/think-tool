<?php


namespace Fearless\ThinkTool\Command;

use think\console\Input;
use think\console\Output;
use think\facade\App;

class TestCommand extends \think\console\Command
{
    public function configure()
    {
        $this->setName('hw:test')
            ->setDescription('test');
    }

    public function execute(Input $input, Output $output)
    {
        $output->writeln('hw-test');
    }
}
