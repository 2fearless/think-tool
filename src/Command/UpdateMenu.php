<?php


namespace Fearless\ThinkTool\Command;

use Fearless\ThinkTool\CreateAdminController;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\App;

class UpdateMenu extends \think\console\Command
{
    public function configure()
    {
        $this->setName('hw:um')
            ->setDescription('更新后台菜单');
    }

    public function execute(Input $input, Output $output)
    {
        if (!$input->getArgument('name')){
            $output->writeln('请填写表名');
            return;
        }
        $name = trim($input->getArgument('name'));
        $res = (new CreateAdminController())->create($name);
        $output->writeln('文件位置'.$res);
    }
}
