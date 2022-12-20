<?php
declare (strict_types=1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class FlowProject extends Model
{
    public static $morph = [
        "relate_model",
        "relate_id",
    ];

    public function project(){
        return $this->morphTo(self::$morph);
    }

    public function steps(){
        return $this->hasMany(FlowStep::class,'flow_project_id');
    }

    public function group(){
        return $this->belongsTo(FlowGroup::class,'flow_group_id');
    }
    //
}
