<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\FlowGroup as FlowGroupModel;
use app\admin\model\FlowNode;
use app\admin\model\FlowProject;
use app\admin\model\FlowStep;
use app\admin\model\SysDepart;
use app\admin\model\SysRole;
use app\common\controller\HwController;
use app\admin\model\FlowTmplStatusEnum;

class FlowGroup extends HwController
{

    public function initdata($name = [])
    {
        $this->addDb(new FlowGroupModel());

        if (in_array('table', $name)) {
            $this->addTable();

            $this->addToolbarAdd();

            $this->addOperateEdit();
            $this->addOperateDelete();
        }

        $require = 'required';
        if (in_array('cols', $name)) {

            $this->addColumnId();
            $this->addColumn('type', '流程分类')->search()->form_type_select([
                '日常办公类' => '日常办公类',
                '人事管理类' => '人事管理类',
                '项目管理类' => '项目管理类',
                '财务管理类' => '财务管理类',
                '供应链管理类' => '供应链管理类',
                '生产管理类' => '生产管理类',
                '客户管理类' => '客户管理类'])->form_width(2);
            $this->addColumn('code', '流程编号')->add($require)->edit($require)->form_width(2);
            $this->addColumn('name', '流程名称')->add($require)->edit($require)->form_width(2);
            $this->addColumn('desc', '流程描述')->add($require)->edit($require)->form_width(2);
            $this->addColumn('relate_name', '对应应用名称')->add($require)->edit($require)->form_width(2);
            $this->addColumn('relate_model', '对应应用模型')->add($require)->edit($require)->form_width(2);
            $this->addColumn('status', '0草稿/1启用/2弃用/3暂停')->sort()->add($require)->edit($require)->form_type_number()->form_width(2);
            $this->addColumn('create_time', '')->add($require)->edit($require)->form_width(2);
            $this->addColumn('update_time', '')->add($require)->edit($require)->form_width(2);

            $this->addRowAction();
        }

        if (in_array('rule', $name)) {
            $this->addRule([
                'type|流程分类' => 'require',
                'code|流程编号' => 'require',
                'name|流程名称' => 'require',
                'desc|流程描述' => 'require',
                'relate_name|对应应用名称' => 'require',
                'relate_model|对应应用模型' => 'require',
                'status|0草稿/1启用/2弃用/3暂停' => 'require',
                'create_time|' => 'require',
                'update_time|' => 'require',

            ]);
        }
    }

    public function add()
    {
        $code = strtoupper(uniqid());
        $departs = SysDepart::field('id as value,name as label')->select();
        $roles = SysRole::field('id as value,name as label')->select();
        if (request()->isPost()) {
            $data = request()->all();
            $nodes = $data['nodes'];
            $node_len = count($nodes);
            $sorts = [];
            foreach ($nodes as $k => &$node) {
                unset($node['id']);
                $node['level'] = $k + 1;
                if (!in_array($node['sort'], $sorts)) {
                    $sorts[] = $node['sort'];
                } else {
                    $node['sort'] = $node['sort'] + 1;
                }
            }
            $name_to_sort = collect($nodes)->column('sort', 'name');
            foreach ($nodes as $k => &$node) {
                if ($k < $node_len - 1) {
                    if (count($node['next_id'])) {
                        $temp = [];
                        foreach ($node['next_id'] as $next) {
                            $temp[] = $name_to_sort[$next];
                        }
                        $node['next_id'] = $temp;
                    } else {
                        $node['next_id'] = [$nodes[$k + 1]['sort']];
                    }
                } else {
                    $node['next_id'] = [0];
                }
            }
            try {
                FlowGroupModel::saveGroup($data['data'], $nodes);
            } catch (\Exception $exception) {
                return json_error($exception->getMessage());
            }
            return json_ok('保存成功');
        }
        $status_options = FlowTmplStatusEnum::options();
        return view('', compact('code', 'departs', 'roles','status_options'));
    }

    public function edit()
    {
        $model = FlowGroupModel::find(input('id'));
        $departs = SysDepart::field('id as value,name as label')->select();
        $roles = SysRole::field('id as value,name as label')->select();
        $nodes = FlowNode::where('flow_group_id', input('id'))->select();
        if (request()->isPost()) {
            $data = request()->all();
            $nodes = $data['nodes'];
            $node_len = count($nodes);
            $sorts = [];
            foreach ($nodes as $k => &$node) {
                unset($node['id']);
                $node['level'] = $k + 1;
                if (!in_array($node['sort'], $sorts)) {
                    $sorts[] = $node['sort'];
                } else {
                    $node['sort'] = $node['sort'] + 1;
                }
            }
            $name_to_sort = collect($nodes)->column('sort', 'name');
            foreach ($nodes as $k => &$node) {
                if ($k < $node_len - 1) {
                    if (count($node['next_id'])) {
                        $temp = [];
                        foreach ($node['next_id'] as $next) {
                            $temp[] = $name_to_sort[$next];
                        }
                        $node['next_id'] = $temp;
                    } else {
                        $node['next_id'] = [$nodes[$k + 1]['sort']];
                    }
                } else {
                    $node['next_id'] = [0];
                }
            }
            try {
                FlowGroupModel::saveGroup($data['data'], $nodes);
            } catch (\Exception $exception) {
                return json_error($exception->getMessage());
            }
            return json_ok('保存成功');
        }
        $sort_to_name = collect($nodes)->column('name', 'sort');
        foreach ($nodes as &$node) {
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
        $is_dealing = FlowStep::cantDel(input('id'));
        $status_options = FlowTmplStatusEnum::options();
        return view('', compact('departs', 'roles', 'model', 'nodes','is_dealing','status_options'));
    }

}
