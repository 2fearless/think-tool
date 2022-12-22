<?php
declare (strict_types=1);

namespace app\admin\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class FlowGroup extends Model
{
    use SoftDelete;

    public function nodes(){
        return $this->hasMany(FlowNode::class,'flow_group_id')->order('sort','asc');
    }

    public function projects(){
        return $this->hasMany(FlowProject::class,'flow_group_id');
    }
    //

    /**
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws Exception
     */
    public static function saveGroup($data, $nodes){
        if (isset($data['delete_time'])){
            unset($data['delete_time']);
        }
        if (isset($data['id'])){
            if (self::where('relate_model',$data['relate_model'])->where('id','<>',$data['id'])->find() || self::where('name',$data['name'])->where('id','<>',$data['id'])->find()){
                throw new Exception('已存在流程，请勿重复添加');
            }
            $group = self::update($data);
        }else{
            if (self::where('relate_model',$data['relate_model'])->find() || self::where('name',$data['name'])->find()){
                throw new Exception('已存在流程，请勿重复添加');
            }
            $group = self::create($data);
        }
        $has_project = FlowProject::where('flow_group_id',$group->id)->find();
        if ($has_project){
            return;
        }
        FlowNode::where('flow_group_id',$group->id)->delete();

        foreach ($nodes as $node){
            $node['flow_group_id'] = $group->id;
            if (isset($node['id'])){
                unset($node['id']);
            }
            if (isset($node['delete_time'])){
                unset($node['delete_time']);
            }
            $node['next_id'] = json_encode($node['next_id']);
            FlowNode::create($node);
        }
    }
}
