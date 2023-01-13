<?php
declare (strict_types=1);

namespace app\admin\model;

use app\common\enums\YesOrNoEnum;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class FlowStep extends Model
{
    use SoftDelete;
    public $append = [
        'image_url'
    ];
    public function getImageUrlAttr()
    {
        return files_url($this->file);
    }
    public function node(){
        return $this->belongsTo(FlowNodeWithDel::class,'flow_node_id');
    }

    public function user(){
        return $this->belongsTo(SysUser::class,'user_id');
    }

    //是否存在未完结流程
    public static function cantDel($id){
        $is_dealing = false;
        $dealing = self::where('flow_group_id',$id)->select();
        //是否存在未完结流程
        $tool = [];
        foreach ($dealing as $item){
            if (!in_array($item['flow_project_id'],$tool)){
                if ($item['finish_flag'] == YesOrNoEnum::NO){
                    $tool[] = $item['flow_project_id'];
                }
            }else{
                if ($item['finish_flag'] == YesOrNoEnum::YES){
                    unset($tool[array_search($item['flow_project_id'],$tool)]);
                }
            }
        }
        if (count($tool)){
            $is_dealing = true;
        }
        return $is_dealing;
    }
}
