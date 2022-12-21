<?php

namespace app\admin\model;

trait FlowTrait
{
    public static function onBeforeInsert($model){
        $class = static::class;
        $group_id = FlowGroup::where('relate_model',$class)->value('id');
        $nodes = FlowNode::where('flow_group_id',$group_id)->order('sort','asc')->select();
        $rule = $nodes[0];
        $ids = SysUser::when($rule['depart_id'] != 1,function ($q) use ($rule){
            $q->where('depart_id',$rule['depart_id']);
        })->when($rule['role_id'] != 1,function ($q) use ($rule){
            $q->where('role_id',$rule['role_id']);
        })->column('id');
        if (!in_array($model['user_id'],$ids) && $model['id'] != 1){
            return false;
        }
    }

    public function flow()
    {
        return $this->morphOne(FlowProject::class, FlowProject::$morph);
    }

    public static function onAfterWrite($model){
        $class = static::class;
        $group_id = FlowGroup::where('relate_model',$class)->value('id');
        $nodes = FlowNode::where('flow_group_id',$group_id)->order('sort','asc')->select();
        $flow_project = FlowProject::where('relate_id',$model->id)
            ->where('relate_model',$class)
            ->where('flow_group_id',$group_id)
            ->find();
        if (!$flow_project){
            $data = [
                'relate_id' => $model->id,
                'relate_model' => $class,
                'flow_group_id' => $group_id
            ];
            $flow_project = FlowProject::create($data);
        }
        $flow_step = FlowStep::where('flow_project_id',$flow_project->id)->order('sort','desc')->find();
        if (!$flow_step){
            $next = FlowNode::where('flow_group_id',$group_id)->whereIn('sort',json_decode($nodes[0]['next_id'],true))->column('id');
            $step = [
                'remark' => '发布',
                'flow_group_id' => $group_id,
                'flow_node_id' => $nodes[0]['id'],
                'finish_flag' => 0,
                'next_flow_node_id' => json_encode($next),
                'sort' => $nodes[0]['sort'],
                'level' => $nodes[0]['level'],
                'user_id' => $model->user_id,
                'flow_project_id' => $flow_project->id
            ];
            $flow_step = FlowStep::create($step);
        }
        $flow_project->flow_node_id = $flow_step->flow_node_id;
        $flow_project->flow_step_id = $flow_step->id;
        $flow_project->user_id = $model->user_id;
        $flow_project->save();
    }
}