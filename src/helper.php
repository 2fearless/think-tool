<?php

use think\facade\Db;
use think\helper\Str;

if (!function_exists('get_cols')) {
    //获取字段分类
    function get_cols($table_name)
    {
        $field_list = Db::query('SHOW FULL COLUMNS FROM `' . $table_name . '`');
        $result = [];
        foreach ($field_list as $item) {
            $temp['pk'] = $item['Key'] == 'PRI';
            $temp['field'] = $item['Field'];
            $temp['number'] = Str::contains($item['Type'], 'int');
            $temp['comment'] = $item['Comment'];
            $result[] = $temp;
        }
        return $result;
    }
}

if (!function_exists('show_tables')) {
    //获取字段分类
    function show_tables()
    {
        $database = config('database.connections')[config('database.default')]['database'];
        $rows = Db::query('show tables from ' . $database);
        return array_column($rows, 'Tables_in_' . $database);
    }
}
if (!function_exists('replace_first')) {
    function replace_first($search, $replace, $subject)
    {
        if ($search === '') {
            return $subject;
        }

        $position = strpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }
}