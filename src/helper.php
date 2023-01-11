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
    //获取当前数据库表及注释
    function show_tables()
    {
        $database = config('database.connections')[config('database.default')]['database'];
        return Db::query("SELECT table_name,table_comment FROM information_schema.TABLES WHERE table_schema = '" . $database . "'");
    }
}

if (!function_exists('show_table_comment')) {
    //获取当前表注释
    function show_table_comment($table)
    {
        $database = config('database.connections')[config('database.default')]['database'];
        return Db::query("SELECT table_comment FROM information_schema.TABLES WHERE table_schema = '" . $database . "' AND table_name = '" . $table . "'")[0]['table_comment'];
    }
}

if (!function_exists('replace_first')) {
    //函数替换字符串中给定值的第一个匹配项
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
if (!function_exists('replace_last')) {
    //函数替换字符串中给定值的最后一个匹配项
    function replace_last($search, $replace, $subject)
    {
        if ($search === '') {
            return $subject;
        }

        $position = strrpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }
}
if (!function_exists('endsWith')) {
    //是否以...结尾
    function endsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if (
                $needle !== '' && $needle !== null
                && substr($haystack, -strlen($needle)) === (string)$needle
            ) {
                return true;
            }
        }

        return false;
    }
}
if (!function_exists('copyDir')){
    function copyDir($dirSrc,$dirTo)
    {
        if(is_file($dirTo))
        {
            echo '目标不是目录不能创建！';
            return;
        }
        if(!file_exists($dirTo))
        {
            mkdir($dirTo);
        }
        $dir_handle = @opendir($dirSrc);
        if($dir_handle)
        {
            while($filename = readdir($dir_handle))
            {
                if($filename!="." && $filename!="..")
                {
                    $subSrcFile = $dirSrc . DIRECTORY_SEPARATOR.$filename;
                    $subToFile = $dirTo . DIRECTORY_SEPARATOR.$filename;

                    if(is_dir($subSrcFile))
                    {
                        copyDir($subSrcFile, $subToFile);
                    }
                    if(is_file($subSrcFile))
                    {
                        copy($subSrcFile, $subToFile);
                    }
                }
            }
            closedir($dir_handle);
        }
    }
}

