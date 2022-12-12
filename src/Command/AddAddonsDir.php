<?php


namespace Fearless\ThinkTool\Command;

use think\console\Input;
use think\console\Output;
use think\facade\App;

class AddAddonsDir extends \think\console\Command
{
    public function configure()
    {
        $this->setName('hw:addons')
            ->setDescription('添加插件目录');
    }

    public function execute(Input $input, Output $output)
    {
        if (!is_dir(root_path('addons'))){
            copyDir(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'addons_dir', root_path('addons'));
        }
        $output->writeln('addons ready');
    }
}
