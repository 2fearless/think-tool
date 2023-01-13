<?php

namespace app\admin\model;

class CheckStatusEnum extends BaseEnum
{
    //草稿
    const DRAFT = 0;
    //待审核
    const CHECKING = 1;
    //审核通过
    const PASS = 2;
    //审核不通过
    const DENY = 3;

    const TYPE = [
        self::DRAFT => '草稿',
        self::CHECKING => '审核中',
        self::PASS => '审核通过',
        self::DENY => '审核不通过'
    ];
}