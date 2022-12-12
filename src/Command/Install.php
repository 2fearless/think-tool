<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Console;
use think\facade\Db;


class Install extends Command
{
    protected $module = '';
    protected $module_dir = '';
    protected $script_class = '';
    protected $except = [
        '.',
        '..'
    ];
    protected $config = [];
    protected function configure()
    {
        // 指令配置
        $this->setName('hw:install')
            ->addArgument('name', Argument::OPTIONAL, "module name")
            ->setDescription('the install command');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!$input->getArgument('name')){
            $output->writeln('请填写模块名');
            return;
        }
        $name = trim($input->getArgument('name'));
        $this->module = $name;
        $this->module_dir = root_path('addons').DIRECTORY_SEPARATOR.$this->module.DIRECTORY_SEPARATOR;
        $this->config = json_decode(file_get_contents($this->module_dir.'config.json'),true);
        //导入sql
        $this->importSql();
        $this->moveScript();
        $this->moveAdminModule();
        $this->moveFrontModule();
        $this->runScript();
        $this->updateMenuAndLock();
        // 指令输出
        $output->writeln('installed['.$this->module.']');
    }

    protected function updateMenuAndLock(){
        Console::call('hw:um');
        touch(root_path('addons').$this->module.DIRECTORY_SEPARATOR.'install.lock');
    }

    protected function moveAdminModule(){
        $dir = $this->module_dir.'admin'.DIRECTORY_SEPARATOR.'controller';
        $files = scandir($dir);
        $except = $this->except;
        foreach ($files as $file){
            if (!in_array($file,$except) && endsWith($file,'.php')){
                copy($dir.DIRECTORY_SEPARATOR.$file, root_path('app/admin/controller').$file);
            }
        }
        $this->moveAdminModel();
        $this->moveAdminView();
    }

    protected function moveAdminView(){
        $dir = $this->module_dir.'admin'.DIRECTORY_SEPARATOR.'view';
        $files = scandir($dir);
        $except = $this->except;
        foreach ($files as $file){
            if (!in_array($file,$except) && is_dir($dir.DIRECTORY_SEPARATOR.$file)){
                if (!is_dir(root_path('app/admin/view').$file)){
                    mkdir(root_path('app/admin/view').$file);
                }
                $views = scandir($dir.DIRECTORY_SEPARATOR.$file);
                foreach ($views as $view){
                    if (!in_array($view,$except) && is_file($dir.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.$view)){
                        copy($dir.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.$view, root_path('app/admin/view').$file.DIRECTORY_SEPARATOR.$view);
                    }
                }
            }
        }
    }

    protected function moveAdminModel(){
        $dir = $this->module_dir.'admin'.DIRECTORY_SEPARATOR.'model';
        $files = scandir($dir);
        $except = $this->except;
        foreach ($files as $file){
            if (!in_array($file,$except) && endsWith($file,'.php')){
                copy($dir.DIRECTORY_SEPARATOR.$file, root_path('app/admin/model').$file);
            }
        }
    }

    protected function moveFrontModule(){
        $dir = $this->module_dir.'front'.DIRECTORY_SEPARATOR.'controller';
        $files = scandir($dir);
        $except = $this->except;
        foreach ($files as $file){
            if (!in_array($file,$except) && endsWith($file,'.php')){
                copy($dir.DIRECTORY_SEPARATOR.$file, root_path('app/'.$this->config['front'].'/controller').$file);
            }
        }
    }

    protected function moveScript(){
        $dir = $this->module_dir;
        $files = scandir($dir);
        $except = [
            '.',
            '..'
        ];
        foreach ($files as $file){
            if (!in_array($file,$except) && endsWith($file,'.php')){
                copy($dir.$file,root_path('extend').$file);
                $this->script_class = str_replace('.php','',$file);
                break;
            }
        }
    }

    protected function runScript(){
        (new $this->script_class)->handle();
    }

    protected function importSql(){
        $module = $this->module;
        $db = new Db();
        $dir = root_path('addons').DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'migration';
        $files = scandir($dir);
        $except = [
            '.',
            '..'
        ];
        $sql = '';
        foreach ($files as $file){
            if (!in_array($file,$except) && endsWith($file,'.sql')){
                $sql = file_get_contents($dir.DIRECTORY_SEPARATOR.$file);
                break;
            }
        }
        if ($sql){
            $sql = str_replace("`hw_",'`'.config('database.connections')[config('database.default')]['prefix'],$sql);
            $list = explode(";\r\n", $sql);
            for ($i = 0; $i < count($list); $i++) {
                if (trim($list[$i])) {
                    $db::execute(trim($list[$i]));
                }
            }
            return;
        }
        $this->runMigration($files,$dir);

    }

    protected function runMigration($files,$dir){
        $except = [
            '.',
            '..'
        ];
        foreach ($files as $file){
            if (!in_array($file,$except) && endsWith($file,'.php')){
                copy($dir.DIRECTORY_SEPARATOR.$file, root_path('database/migrations').$file);
            }
        }
        Console::call('migrate:run');
    }


}
