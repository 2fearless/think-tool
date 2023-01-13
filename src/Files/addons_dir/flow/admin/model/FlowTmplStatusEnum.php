<?php

namespace app\admin\model;

class FlowTmplStatusEnum extends BaseEnum
{
    //草稿
    const PREPARE = 0;
    //启用
    const OPEN = 1;
    //弃用
    const DISUSE = 2;
    //暂停
    const PAUSE = 3;

    const TYPE = [
        self::PREPARE => '草稿',
        self::OPEN => '启用',
        self::DISUSE => '弃用',
        self::PAUSE => '暂停'
    ];
}