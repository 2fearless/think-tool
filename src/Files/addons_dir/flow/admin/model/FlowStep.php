<?php
declare (strict_types=1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class FlowStep extends Model
{
    public function node(){
        return $this->belongsTo(FlowNode::class,'flow_node_id');
    }

    public function user(){
        return $this->belongsTo(SysUser::class,'user_id');
    }
    //
}
