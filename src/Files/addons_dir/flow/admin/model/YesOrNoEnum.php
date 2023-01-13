<?php

namespace app\admin\model;

class YesOrNoEnum extends BaseEnum
{
    const YES = 1;
    const NO = 0;

    const TYPE = [
        self::YES => '是',
        self::NO => '否'
    ];
}