<?php
declare (strict_types=1);

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class FlowNode extends FlowNodeWithDel
{
    public $table = 'hw_flow_node';
    use SoftDelete;
    //
}
