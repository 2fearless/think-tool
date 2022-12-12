<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\ActivityUser as ActivityUserModel;
use app\admin\model\SysUser;
use app\common\controller\HwController;

class ActivityUser extends HwController
{
    public function initdata($name = [])
    {
        $this->addDb(new ActivityUserModel());

        if (in_array('table', $name)) {
            $this->addTable();

//            $this->addToolbarAdd();
//
//            $this->addOperateEdit();
            $this->addOperateDelete();
        }

        $require = 'required';
        if (in_array('cols', $name)) {

            $this->addColumnId();
            $this->addColumn('user_id', '用户')->form_type_select(
                SysUser::column('nickname', 'id')
            )->form_width(2)->table_width(120)->search()->detail();
            $this->addColumn('activity_id', '活动')->form_type_select(
                \app\admin\model\Activity::column('name', 'id')
            )->form_width(2)->table_width(120)->search()->detail();

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
