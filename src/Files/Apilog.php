<?php

namespace app\admin\controller\sys;

use app\admin\model\SysApilog;
use app\admin\model\SysOperationlog;
use app\common\controller\HwController;

class Apilog extends HwController
{
    public function initdata($name = [])
    {
        $this->addDb(SysApilog::with([
            'user' => fn($q) => $q->field(['id', 'nickname'])
        ]));

        if (in_array('table', $name)) {
            $this->addTable();
            $this->addToolbar('delete', '删除30天前记录')->color('danger');
        }

        if (in_array('cols', $name)) {
            $this->addColumn('id', 'ID')->table_width(60);

            $this->addColumn('uid', '用户名')->search()->form_type_ajaxselect([
                'url' => 'sysUsers', 'relation' => 'user', 'name' => 'nickname' // name 为 关联表显示字段
            ])->table_format(fn($v, $model) => $model->user->nickname ?? '-')->table_width(120);

            $this->addColumn('status', '状态')->search()->form_type_select([
                1 => '操作成功', 0 => '操作失败'
            ])->table_width(100);

            $this->addColumn('info', '请求方式')->table_width(100);

            $this->addColumn('get', '链接')->table_width(240);
            $this->addColumn('param', '请求参数')->table_width(200);
            $this->addColumn('echo', '响应参数')->table_width(200);
            $this->addColumn('spent', '响应时间')->table_width(100);

            $this->addColumnCreateTime('操作时间', 'time')->hide(false)->table_width(100);
        }
    }

    //删除一个月前的登录信息
    public function delete()
    {
        if (SysApilog::where('time', '<=', today()->subDays(30))->delete()) {
            $this->success('删除成功！');
        }
        $this->error('删除失败！');
    }
}