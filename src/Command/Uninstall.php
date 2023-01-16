<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Console;
use think\facade\Db;
use \Phinx\Util\Util;

class Uninstall extends Command
{
    protected $module = '';
    protected $module_dir = '';
    protected $script_class = '';
    protected $remove_dir_list = [
        'admin' . DIRECTORY_SEPARATOR . 'controller',
        'admin' . DIRECTORY_SEPARATOR . 'model',
        'admin' . DIRECTORY_SEPARATOR . 'view',
        'front' .DIRECTORY_SEPARATOR. 'controller'
    ];
    protected $except = [
        '.',
        '..'
    ];
    private $config;
    protected $db_prefix = '';

    protected function configure()
    {
        // 指令配置
        $this->setName('hw:uninstall')
            ->addArgument('name', Argument::OPTIONAL, "module name")
            ->setDescription('the install command');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!$input->getArgument('name')) {
            $output->writeln('请填写模块名');
            return;
        }
        $name = trim($input->getArgument('name'));
        $this->module = $name;
        $this->module_dir = root_path('addons') . $this->module . DIRECTORY_SEPARATOR;
        $this->config = json_decode(file_get_contents($this->module_dir . 'config.json'), true);
        $this->db_prefix = config('database.connections')[config('database.default')]['prefix'];
        //移除数据
        $this->removeSql();
//        $this->moveScript();
        $this->RemoveModule();
        //移除安装锁
        $this->updateMenuAndUnLock();
        // 指令输出
        $output->writeln('uninstalled [' . $this->module . ']');
    }

    //删除admin模块
    public function RemoveModule()
    {
        $dirList = $this->remove_dir_list;
        foreach ($dirList as $dirSrc) {
            $removeFileList = $this->getAddonsFileList($dirSrc)['file_list'];
            $dirList = $this->getAddonsFileList($dirSrc)['dir_list'];
            //删除指定文件
            foreach ($removeFileList as $file) {
                $file = str_replace('front','api',$file);
                $removeFile = root_path('app') . $file;
                if (is_file($removeFile)) {
                    unlink($removeFile);
                }
            }
            //删除空文件夹
            foreach ($dirList as $folder) {
                if (is_dir(root_path('app') . $folder)) {
                    $folderList = scandir(root_path('app') . $folder);
                    $folderList = array_filter($folderList, function ($arr) {
                        if (!in_array($arr, $this->except)) {
                            return $arr;
                        }
                        return [];
                    });
                    if (count($folderList) == 0) {
                        rmdir(root_path('app') . $folder);
                    }
                }
            }
        }
    }

    //获取插件文件的列表
    public function getAddonsFileList($dirSrc): array
    {
        $arr = [
            'file_list' => [],
            'dir_list' => []
        ];
        if (is_file(root_path('addons') . $this->module . DIRECTORY_SEPARATOR . $dirSrc)) {
            $arr['file_list'][] = $dirSrc;
        }
        if (is_dir(root_path('addons') . $this->module . DIRECTORY_SEPARATOR . $dirSrc)) {
            $arr['dir_list'][] = $dirSrc;
            $dirList = scandir(root_path('addons') . $this->module . DIRECTORY_SEPARATOR . $dirSrc);
            $dirList = array_filter($dirList, function ($arr) {
                if (!in_array($arr, $this->except)) {
                    return $arr;
                }
                return [];
            });
            foreach ($dirList as $dir) {
                $dirList = $this->getAddonsFileList($dirSrc . DIRECTORY_SEPARATOR . $dir);
                foreach ($dirList['file_list'] as $value) {
                    if (count($dirList)) {
                        $arr['file_list'][] = $value;
                    };
                }
                foreach ($dirList['dir_list'] as $value) {
                    if (count($dirList)) {
                        $arr['dir_list'][] = $value;
                    };
                }
            }
        }
        return $arr;
    }

    //更新菜单和解锁
    public function updateMenuAndUnLock()
    {
        Console::call('hw:um');
        $lockFile = root_path('addons') . $this->module . DIRECTORY_SEPARATOR . 'install.lock';
        if (file_exists($lockFile)) {
            unlink($lockFile);
        }
    }

    //删除文件
    public function removeFile($dirSrc)
    {
        if (is_file($dirSrc)) {
            unlink($dirSrc);
        }
        if (is_dir($dirSrc)) {
            $dirList = scandir($dirSrc);
            $dirList = array_filter($dirList, function ($arr) {
                if (!in_array($arr, $this->except)) {
                    return $arr;
                }
                return [];
            });
            $removeIndex = count($dirList);
            foreach ($dirList as $dir) {
                $this->removeFile($dirSrc . DIRECTORY_SEPARATOR . $dir);
                $removeIndex--;
            };
            if ($removeIndex == 0) {
                rmdir($dirSrc);
            }
        }
    }

    protected function removeSql()
    {
        $module = $this->module;
        $db = new Db();
        $dir = root_path('addons') . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'migration';
        $files = scandir($dir);
        $except = [
            '.',
            '..'
        ];
        $sql = '';
        foreach ($files as $file) {
            if (!in_array($file, $except) && endsWith($file, '.sql')) {
                $sql = file_get_contents($dir . DIRECTORY_SEPARATOR . $file);
                break;
            }
        }
        if ($sql) {
            $sql = str_replace("`hw_", '`' . config('database.connections')[config('database.default')]['prefix'], $sql);
            $list = explode(";\r\n", $sql);
            for ($i = 0; $i < count($list); $i++) {
                if (trim($list[$i])) {
                    $db::execute(trim($list[$i]));
                }
            }
            return;
        }
        $this->removeMigration($files, $dir);

    }

    protected function removeMigration($files, $dir)
    {
        $except = [
            '.',
            '..'
        ];
        $remove_files = [];
        //查找需要删除的model文件
        foreach ($files as $file) {
            if (!in_array($file, $except) && endsWith($file, '.php')) {
                $filePath = root_path('database/migrations') . $file;
                if (is_file($filePath)) {
                    array_push($remove_files, $file);
                    $class = Util::mapFileNameToClassName(basename($filePath));
                    $this->dropTableWithClassName($class);
                }
                $this->removeFile($filePath);
            }
        }
        Console::call('migrate:run');
    }
    //删除表
    protected function dropTableWithClassName($class)
    {
        $tableName = $this->camelize($class);
        $this->dropTable($tableName);
        $this->removeMigrationLog($class);
    }

    //删除指定表
    protected function dropTable($tableName)
    {
        $result = Db::query("DROP TABLE IF EXISTS {$this->db_prefix}{$tableName};");
    }

    //移除migrate日志
    protected function removeMigrationLog($className)
    {
        Db::name('migrations')->where(['migration_name' => $className])->delete(true);
    }

    //下划线转驼峰
    function camelize($str)
    {
        $dstr = preg_replace_callback('/([A-Z]+)/', function ($matchs) {
            return '_' . strtolower($matchs[0]);
        }, $str);
        return trim(preg_replace('/_{2,}/', '_', $dstr), '_');
    }

    function uncamelize($string)
    {
        return preg_replace('/([a-z])([A-Z])/e', '"$1" . strtolower("_$2")', $string);
    }

}