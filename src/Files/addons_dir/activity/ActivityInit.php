<?php
use app\admin\model\TestTest;

class ActivityInit {
    public function handle(){
        $activity = \app\admin\model\Activity::create(['name'=>'活动1','content'=>'6666']);
        \app\admin\model\ActivityUser::create(['user_id'=>1,'activity_id'=>$activity['id']]);
    }
}
