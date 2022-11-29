<?php


namespace Fearless\ThinkTool\Command;

use Fearless\ThinkTool\CreateAdminController;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\App;
use think\facade\Db;
use think\facade\Console;

class UpdateMenu extends \think\console\Command
{
    public function configure()
    {
        $this->setName('hw:um')
            ->setDescription('更新后台菜单');
    }

    public function execute(Input $input, Output $output)
    {
        Console::call('seed:run');
        $menu_class = $this->hasMenuController($this->allClasses());
        foreach ($menu_class as $item){
            $this->fillMenu($item::$menu);
        }
        $output->writeln('菜单更新完毕');
    }
    //目录下所有控制器
    public function allClasses(){
        $module = 'admin';
        $path = root_path('app/'.$module.'/controller');
        $files = scandir($path);
        $classes = [];
        $namespace = 'app\\'.$module.'\controller';
        foreach ($files as $file){
            if(endsWith($file,'.php')){
                $classes[] = $namespace.'\\'.replace_last('.php','',$file);
            }
        }
        return $classes;
    }
    //拥有menu配置的控制器
    public function hasMenuController($classes){
        $list = [];
        foreach ($classes as $class){
            if (property_exists($class,'menu')){
                $list[] = $class;
            }
        }
        return $list;
    }

    public function fillMenu($data){
        $now = now()->toDateTimeString();
        [$temp, $id] = $this->formatData($data, [], 0, $now, 0);
        Db::table('sys_menu')->insert($temp)->saveData();
    }
    private function formatData($data, $temp, $id, $now, $pid)
    {
        foreach ($data as $k => $v) {
            $id += 1;
            $v['id'] = $id;
            $v['pid'] = $pid;
            $v['create_time'] = $now;
            $v['update_time'] = $now;
            $son = $v['son'] ?? false;
            unset($v['son']);
            $temp[] = $v;
            if ($son) {
                [$temp, $id] = $this->formatData($son, $temp, $id, $now, $id);
            }
        }
        return [$temp, $id];
    }
}
