<?php
declare (strict_types=1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class FlowNodeWithDel extends Model
{
    public $table = 'hw_flow_node';
    //
}
