<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\FlowNode;
use app\admin\model\FlowProject;
use app\admin\model\FlowStep;
use app\admin\model\NewProject as NewProjectModel;
use app\admin\model\SysUser;
use app\common\controller\HwController;

class NewProject extends HwController
{
    use FlowControllerTrait;
    public static $menu = [
        'name' => '测试项目管理', 'type' => 0, 'icon' => 'layui-icon-tabs', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
            ['name' => '测试项目列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '测试项目添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目编辑', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'edit', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目删除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目详情', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'detail', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目进度', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'step', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目添加进度', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'forward', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
//              ['name' => '测试项目导入', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'import', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
//              ['name' => '测试项目导出', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'export', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
        ]]
    ]];
    public function initdata($name = [])
    {
        $this->addDb(new NewProjectModel());

        if (in_array('table', $name)) {
            $this->addTable();

            $this->addToolbarAdd();
            $this->addStepButton();
            $this->addOperateEdit();
            $this->addOperateDelete();
        }

        $require = 'required';
        if (in_array('cols', $name)) {

            $this->addColumnId();
            $this->addColumn('name', '名称')->add($require)->edit($require);
            $this->addColumn('user_id', '用户id')->sort()->add($require)->edit($require)->form_type_number();
            $this->addColumn('create_time', '创建时间');
            $this->addColumn('update_time', '更新时间');

            $this->addRowAction()->table_width(300);
        }

        if (in_array('rule', $name)) {
            $this->addRule([
                'name|名称' => 'require',
                'user_id|用户id' => 'require',
            ]);
        }
    }
}
