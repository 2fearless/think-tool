<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\TestTest as TestTestModel;
use app\common\controller\HwController;

class TestTest extends HwController
{
    public static $menu = [
        'name' => '测试管理', 'type' => 0, 'icon' => 'layui-icon-tabs', 'app' => 'admin', 'controller' => 'TestTest', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
            ['name' => '测试列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'TestTest', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '测试添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'TestTest', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试编辑', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'TestTest', 'action' => 'edit', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试删除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'TestTest', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试详情', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'TestTest', 'action' => 'detail', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试按钮', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'TestTest', 'action' => 'ok', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
              //['name' => '接口请求日志导入', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'SysApilog', 'action' => 'import', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
              //['name' => '接口请求日志导出', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'SysApilog', 'action' => 'export', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
        ]]
    ]];
    public function initdata($name = [])
    {
        $this->addDb(new TestTestModel());

        if (in_array('table', $name)) {
            $this->addTable();

            $this->addToolbarAdd();
            $this->addOperate('ok', '测试按钮')->color('warm')->type('detail');
            $this->addOperateEdit();
            $this->addOperateDelete();
        }

        $require = 'required';
        if (in_array('cols', $name)) {

            $this->addColumnId();
			$this->addColumn('title', '标题')->sort()->add($require)->edit($require)->form_width(2);

            $this->addRowAction();
        }

        if (in_array('rule', $name)) {
            $this->addRule([
                'test|测试' => 'require',
            ]);
        }
    }

    public function ok(){
        return view();
    }

}
