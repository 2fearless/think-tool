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
            $this->addOperate('step', '查看进度')->color('primary')->type('edit');
            $this->addOperate('forward', '添加进度')->color('warm')->type('edit');
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

    protected function hw_after_delete($id)
    {
        $flow_project = FlowProject::where('relate_model', NewProjectModel::class)->where('relate_id', $id)->find();
        $flow_project_id = $flow_project['id'];
        FlowStep::where('flow_project_id', $flow_project_id)->select()->delete();
        $flow_project->delete();
    }

    public function step()
    {
        $project = NewProjectModel::with(['flow' => ['group.nodes', 'steps' => ['user', 'node']]])->find(input('id'));
        $nodes = $project->flow->group->nodes->toArray();
        $model = $project->flow->group->toArray();
        $steps = $project->flow->steps->toArray();
        $last_node = $steps[count($steps) - 1];
        $sort_to_name = collect($nodes)->column('name', 'sort');
        $sort_to_name[$last_node['node']['sort']] = $last_node['node']['name'] . '(当前)';
        foreach ($nodes as &$node) {
            if ($node['sort'] == $last_node['node']['sort']) {
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
        $nodes = json_encode($nodes, JSON_UNESCAPED_UNICODE);
        $model = json_encode($model, JSON_UNESCAPED_UNICODE);
        $project_name = $project->name;
        return view('flow_type/step', compact('model', 'nodes', 'project_name', 'steps'));
    }

    public function forward()
    {
        $id = input('id');
        if (request()->isPost()) {
            $data = request()->all();
            $flow = FlowProject::where('relate_model', NewProjectModel::class)->where('relate_id', $id)->find();
            $final = FlowNode::find($data['final']);
            $next = FlowNode::find($data['next_id']);
            $finish_flag = $data['final'] == $data['next_id'] ? 1 : 0;
            switch ($data['way']) {
                case 'final':
                    $fill = [
                        'remark' => $data['remark'],
                        'file' => $data['file'],
                        'flow_group_id' => $flow['flow_group_id'],
                        'flow_project_id' => $flow['id'],
                        'flow_node_id' => $data['final'],
                        'finish_flag' => 1,
                        'next_flow_node_id' => '[0]',
                        'user_id' => $data['user_id'],
                        'sort' => $final['sort'],
                        'level' => $final['level']
                    ];
                    $flow = FlowStep::create($fill);
                    $flow->flow_node_id = $fill['flow_node_id'];
                    $flow->flow_step_id = $flow->id;
                    $flow->save();
                    break;
                case 'back':
                    $back = FlowStep::find($data['back']);
                    $fill = [
                        'remark' => $back['remark'] . '(回退)',
                        'file' => $back['file'],
                        'flow_group_id' => $back['flow_group_id'],
                        'flow_project_id' => $back['flow_project_id'],
                        'flow_node_id' => $back['flow_node_id'],
                        'finish_flag' => $back['finish_flag'],
                        'next_flow_node_id' => $back['next_flow_node_id'],
                        'user_id' => $back['user_id'],
                        'sort' => $back['sort'],
                        'level' => $back['level']
                    ];
                    $flow = FlowStep::create($fill);
                    $flow->flow_node_id = $fill['flow_node_id'];
                    $flow->flow_step_id = $flow->id;
                    $flow->save();
                    break;
                case 'next':
                    $fill = [
                        'remark' => $data['remark'],
                        'file' => $data['file'],
                        'flow_group_id' => $flow['flow_group_id'],
                        'flow_project_id' => $flow['id'],
                        'flow_node_id' => $data['next_id'],
                        'finish_flag' => $finish_flag,
                        'next_flow_node_id' => json_encode(FlowNode::whereIn('sort',json_decode($next['next_id'],true))->where('flow_group_id',$next['flow_group_id'])->column('id')),
                        'user_id' => $data['user_id'],
                        'sort' => $next['sort'],
                        'level' => $next['level']
                    ];
                    $flow = FlowStep::create($fill);
                    $flow->flow_node_id = $fill['flow_node_id'];
                    $flow->flow_step_id = $flow->id;
                    $flow->save();
                    break;
                default:

            }
            return json_ok('操作成功');
        }
        $project = NewProjectModel::with(['flow' => ['group.nodes', 'steps' => ['user', 'node']]])->find($id)->toArray();
        $steps = $project['flow']['steps'];
        $current_step = $steps[count($steps) - 1];
        $key_node = [];
        foreach ($project['flow']['group']['nodes'] as $item) {
            $key_node[$item['id']] = $item;
        }
        $final = $item;
        $final_id = $final['id'];
        if (count($steps) > 1) {
            $back = $steps[count($steps) - 2];
            $back_id = $back['id'];
        } else {
            $back = [];
            $back_id = 0;
        }
        $next_ids = json_decode($current_step['next_flow_node_id'], true);
        $next = [];
        foreach ($next_ids as $next_id) {
            $next[] = $key_node[$next_id];
        }
        $users = SysUser::column('nickname', 'id');
        $has_next = 1;
        if ($current_step['flow_node_id'] == $final['id']) {
            $has_next = 0;
        }
        return view('flow_type/forward', compact('id', 'back', 'next', 'final', 'users', 'back_id', 'final_id', 'has_next'));
    }

}
