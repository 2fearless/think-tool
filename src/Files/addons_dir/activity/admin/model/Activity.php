<?php

namespace app\admin\model;

use think\Model;

class Activity extends Model
{
    public function users(){
        return $this->belongsToMany(SysUser::class,'activity_user','user_id','activity_id');
    }
}