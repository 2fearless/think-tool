<?php

namespace app\admin\model;

class ThroughEnum extends BaseEnum
{
    //步骤中
    const DEALING = 0;
    //通过
    const PASS = 1;
    //不通过
    const DENY = -1;

    const TYPE = [
        self::DEALING => '审核中',
        self::PASS => '审核通过',
        self::DENY => '审核不通过'
    ];
}