<?php

namespace app\admin\model;

use think\Model;

class ActivityUser extends Model
{
    public function activity(){
        return $this->belongsTo(Activity::class,'activity_id');
    }

    public function user(){
        return $this->belongsTo(SysUser::class,'user_id');
    }

}