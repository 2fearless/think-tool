<?php

namespace app\admin\model;

use think\Model;

class SysApilog extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'time';

    // 关闭自动写入update_time字段
    protected $updateTime = false;

    public function user()
    {
        return $this->belongsTo(SysUser::class, 'uid');
    }

    public function getUserNicknameAttr() {
        return $this->user->nickname ?? '-';
    }
}