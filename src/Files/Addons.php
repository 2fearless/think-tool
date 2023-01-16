<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\News as NewsModel;
use app\common\controller\HwController;
use think\facade\Console;
use think\facade\Db;

class Addons extends HwController
{
    public function add(){
       $module = input('module');
       Console::call('hw:install',[$module]);
       return json_ok('安装'.$module.'完成');
    }

    public function remove(){
        $module = input('module');
        try {
            Console::call('hw:uninstall',[$module]);
        }catch (\Exception $exception){
            return json_error($exception->getMessage());
        }
        return json_ok('卸载'.$module.'完成');
    }

    public function index(){
        if (request()->isPost()){
            return json_error('参数错误');
        }
        $a = 123;
        return view('',compact('a'));
    }

    public function list(){
        $path = root_path('addons');
        if (!is_dir($path)){
           return [
                "code"=>0,
                "msg"=>'',
                "count"=>0,
                "data" => []
            ];
        }
        $dirs = scandir($path);
        $except = [
            '.',
            '..'
        ];
        $modules = [];
        $i = 0;
        foreach ($dirs as $dir){
            if (is_dir($path.DIRECTORY_SEPARATOR.$dir) && !in_array($dir,$except)){
                $i++;
                $temp = json_decode(file_get_contents($path.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'config.json'),true);
                $temp['module'] = $dir;
                $temp['installed'] = is_file($path.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'install.lock');
                $modules[$dir] = $temp;
            }
        }
        $data = [
            "code"=>0,
            "msg"=>'',
            "count"=>$i,
            "data" => $modules
        ];

        return $data;
    }
}
