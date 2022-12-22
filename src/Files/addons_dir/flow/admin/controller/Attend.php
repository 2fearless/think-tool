<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\Attend as AttendModel;
use app\admin\model\SysUser;
use app\common\controller\HwController;

class Attend extends HwController
{
    use FlowControllerTrait;

    public static $menu = [
        'name' => '活动报名管理', 'type' => 0, 'icon' => 'layui-icon-tabs', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
            ['name' => '活动报名列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '活动报名添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动报名编辑', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'edit', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动报名删除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动报名详情', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'detail', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动报名进度', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'step', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '活动报名添加进度', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'forward', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
//              ['name' => '活动报名导入', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'import', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
//              ['name' => '活动报名导出', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'Attend', 'action' => 'export', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
            ]],
        ]];

    public function initdata($name = [])
    {
        $this->addDb(new AttendModel());

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
            $this->addColumn('name', '名称')->add($require)->edit($require)->form_width(2);
            $this->addColumn('user_id', '用户')->form_type_select(SysUser::column('nickname', 'id'))->sort()->add($require)->edit($require);
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
