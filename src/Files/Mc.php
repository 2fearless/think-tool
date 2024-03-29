<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\News as NewsModel;
use app\common\controller\HwController;
use think\facade\Console;
use think\facade\Db;

class Mc extends HwController
{
    public static $menu = [
        'name' => '开发管理', 'type' => 0, 'icon' => 'layui-icon-component', 'app' => 'admin', 'controller' => 'sys.dev', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 102, 'son' => [
            ['name' => '创建控制器', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'mc', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '控制器添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'mc', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
            ]],
            ['name' => '接口日志', 'type' => 1, 'icon' => '', 'app' => 'admin', 'controller' => 'sys.apilog', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '删除30天前接口日志', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'sys.apilog', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
            ]],
            ['name' => '插件列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'Addons', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '插件添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Addons', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '插件移除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Addons', 'action' => 'remove', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '插件接口', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Addons', 'action' => 'list', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '插件文档', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Addons', 'action' => 'readme', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
            ]]
        ]
    ];
    public function index(){
        if (request()->isPost()){
            $table = request()->post('table');
            $flash = request()->post('flash');
            if ($table){
                return json_ok(Console::call('hw:mc',[$table])->fetch());
            }
            if ($flash){
                return json_ok(Console::call('hw:um')->fetch());
            }
            return json_error('参数错误');
        }
        $list = show_tables();
        foreach ($list as $k=>$item){
            $list[$k]['name'] = replace_first(config('database.connections')[config('database.default')]['prefix'],'',$item['table_name']);
        }
        return view('',compact('list'));
    }
}
