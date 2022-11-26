<?php

use think\facade\Db;
use think\helper\Str;

if (!function_exists('get_cols')){
    //获取字段分类
    function get_cols($table_name){
        $field_list = Db::query('SHOW FULL COLUMNS FROM `' . $table_name . '`');
        $result = [];
        foreach ($field_list as $item){
            $temp['pk'] = $item['Key'] == 'PRI';
            $temp['field'] = $item['Field'];
            $temp['number'] = Str::contains($item['Type'], 'int');
            $temp['comment'] = $item['Comment'];
            $result[] = $temp;
        }
        return $result;
    }
}