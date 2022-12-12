<?php

namespace app\api\controller;

use app\common\controller\ApiController;

class TestTest extends ApiController
{
    // 安全返回
    public function index()
    {
        return json(['code' => 1, 'msg' => '系统运行正常']);
    }
}
