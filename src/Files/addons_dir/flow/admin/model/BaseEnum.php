<?php

namespace app\admin\model;

class BaseEnum
{
    const TYPE = [];

    //下拉组件需要的key-value数据格式
    public static function options(){
        $status = static::TYPE;
        $status_options = [];
        foreach ($status as $k=>$v){
            $tmp['label'] = $v;
            $tmp['value'] = $k;
            $status_options[] = $tmp;
        }
        return $status_options;
    }
}