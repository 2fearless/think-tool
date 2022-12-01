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
        //刷新seed中目录
        Console::call('seed:run',['-s','SysMenuSeeder']);
        $menu_class = $this->hasMenuController($this->allClasses());
        foreach ($menu_class as $item){
            $this->fillMenu([$item::$menu]);
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

    //单个模块填充
    public function fillMenu($data){
        $now = now()->toDateTimeString();
//        $prefix = config('database.connections')[config('database.default')]['prefix'];
        foreach ($data as $v1){
            $son1 = $v1['son'];
            unset($v1['son']);
            $v1['create_time'] = $now;
            $v1['update_time'] = $now;
            $v1id = Db::name('sys_menu')->insertGetId($v1);
            foreach ($son1 as $v2){
                $son2 = $v2['son'];
                $v2['pid'] = $v1id;
                $v2['create_time'] = $now;
                $v2['update_time'] = $now;
                unset($v2['son']);
                $v2id = Db::name('sys_menu')->insertGetId($v2);
                foreach ($son2 as $v3){
                    $v3['create_time'] = $now;
                    $v3['update_time'] = $now;
                    $v3['pid'] = $v2id;
                    Db::name('sys_menu')->insertGetId($v3);
                }
            }
        }
    }
}
