<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\NewProject as NewProjectModel;
use app\common\controller\HwController;

class NewProject extends HwController
{
    public static $menu = [
        'name' => '测试项目管理', 'type' => 0, 'icon' => 'layui-icon-tabs', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
            ['name' => '测试项目列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '测试项目添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目编辑', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'edit', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目删除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目详情', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'detail', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '测试项目进度', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => 'NewProject', 'action' => 'step', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
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
            $this->addOperate('step', '查看进度')->color('primary')->type('edit');
            $this->addOperateEdit();
            $this->addOperateDelete();
        }

        $require = 'required';
        if (in_array('cols', $name)) {

            $this->addColumnId();
			$this->addColumn('name', '名称')->add($require)->edit($require)->form_width(2);
			$this->addColumn('user_id', '用户id')->sort()->add($require)->edit($require)->form_type_number()->form_width(2);
			$this->addColumn('create_time', '创建时间')->form_width(2);
			$this->addColumn('update_time', '更新时间')->form_width(2);

            $this->addRowAction();
        }

        if (in_array('rule', $name)) {
            $this->addRule([
				'name|名称' => 'require',
				'user_id|用户id' => 'require',
            ]);
        }
    }

    public function step(){
        $project = NewProjectModel::with(['flow'=>['group.nodes','steps'=>['user','node']]])->find(input('id'));
        $nodes = $project->flow->group->nodes->toArray();
        $model = $project->flow->group->toArray();
        $steps = $project->flow->steps->toArray();
        $last_node = $steps[count($steps) - 1];
        $sort_to_name = collect($nodes)->column('name', 'sort');
        $sort_to_name[$last_node['node']['sort']] = $last_node['node']['name'].'(当前)';
        foreach ($nodes as &$node) {
            if ($node['sort'] == $last_node['node']['sort']){
                $node['name'] = $sort_to_name[$last_node['node']['sort']];
            }
            $node['id'] = $node['name'];
            $node['label'] = $node['name'];
            $node['value'] = $node['name'];
            $node['next_id'] = json_decode($node['next_id'], true);
            if (count($node['next_id']) && $node['next_id'][0] != 0) {
                $temp = [];
                foreach ($node['next_id'] as $next) {
                    $temp[] = $sort_to_name[$next];
                }
                $node['next_id'] = $temp;
            } else {
                $node['next_id'] = [];
            }
        }
        unset($model['nodes']);
        $nodes = json_encode($nodes,JSON_UNESCAPED_UNICODE);
        $model = json_encode($model,JSON_UNESCAPED_UNICODE);
        $project_name = $project->name;
        return view('', compact('model', 'nodes','project_name','steps'));
    }

}
