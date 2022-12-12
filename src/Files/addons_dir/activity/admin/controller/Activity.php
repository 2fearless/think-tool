<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\Activity as ActivityModel;
use app\common\controller\HwController;

class Activity extends HwController
{
    public static $menu = [
        'name' => '活动管理', 'type' => 0, 'icon' => 'layui-icon-tabs', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
            ['name' => '活动列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '活动添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动编辑', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'edit', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动删除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动详情', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'detail', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动报名', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'join', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动预览', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Activity', 'action' => 'page', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
              //['name' => '接口请求日志导入', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'SysApilog', 'action' => 'import', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
              //['name' => '接口请求日志导出', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'SysApilog', 'action' => 'export', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
        ]],
            ['name' => '活动报名列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'ActivityUser', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '活动添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'ActivityUser', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动编辑', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'ActivityUser', 'action' => 'edit', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动删除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'ActivityUser', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动详情', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'ActivityUser', 'action' => 'detail', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                //['name' => '接口请求日志导入', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'SysApilog', 'action' => 'import', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                //['name' => '接口请求日志导出', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'SysApilog', 'action' => 'export', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
            ]]
    ]];
    public function initdata($name = [])
    {
        $this->addDb(new ActivityModel());

        if (in_array('table', $name)) {
            $this->addTable();

            $this->addToolbarAdd();
            $this->addOperate('page', '活动预览')->color('warm')->type('detail');
            $this->addOperateEdit();
            $this->addOperateDelete();
        }

        $require = 'required';
        if (in_array('cols', $name)) {
            $this->addColumnId();
			$this->addColumn('name', '名称')->add($require)->edit($require)->form_width(2);
			$this->addColumn('content', '内容')->add($require)->edit($require)->form_width(2);
			$this->addColumn('images', '图片')->add($require)->form_type_images('activity')->edit($require)->form_width(2);
			$this->addColumn('sort', '排序')->add($require)->edit($require)->form_width(2)->sort();
            $this->addRowAction();
        }

        if (in_array('rule', $name)) {
            $this->addRule([
            ]);
        }
    }

    public function page(){
        $id = input('id');
        $data = \app\admin\model\Activity::where('id',$id)->find();
        return view('',compact('data'));
    }

}
