<?php
use app\admin\model\TestTest;

class FlowInit {
    public function handle(){
        //添加第一个流程
        $data = json_decode('{"type":"日常办公类","code":"LCBH63A3B6E7078C8","name":"报名审核","desc":"活动报名流程","relate_name":"活动报名","relate_model":"app\\\admin\\\model\\\Attend","status":1,"create_time":"2022-12-22 09:48:58","update_time":"2022-12-22 10:04:52","delete_time":null}',true);
        $nodes = json_decode('[{"id":"开始","flow_group_id":13,"name":"开始","next_id":["地方审核"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":1,"level":1,"create_time":"2022-12-22 09:48:58","update_time":"2022-12-22 09:48:58","delete_time":null,"label":"开始","value":"开始"},{"id":"地方审核","flow_group_id":13,"name":"地方审核","next_id":["中央审核"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":11,"level":2,"create_time":"2022-12-22 09:48:58","update_time":"2022-12-22 09:48:58","delete_time":null,"label":"地方审核","value":"地方审核"},{"id":"中央审核","flow_group_id":13,"name":"中央审核","next_id":["主办方审核","代表审核"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":21,"level":3,"create_time":"2022-12-22 09:48:58","update_time":"2022-12-22 09:48:58","delete_time":null,"label":"中央审核","value":"中央审核"},{"id":"主办方审核","flow_group_id":13,"name":"主办方审核","next_id":["代表审核"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":31,"level":4,"create_time":"2022-12-22 09:48:58","update_time":"2022-12-22 09:48:58","delete_time":null,"label":"主办方审核","value":"主办方审核"},{"id":"代表审核","flow_group_id":13,"name":"代表审核","next_id":["归档"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":35,"level":5,"create_time":"2022-12-22 09:48:58","update_time":"2022-12-22 09:48:58","delete_time":null,"label":"代表审核","value":"代表审核"},{"id":"归档","flow_group_id":13,"name":"归档","next_id":[],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":41,"level":6,"create_time":"2022-12-22 09:48:58","update_time":"2022-12-22 09:48:58","delete_time":null,"label":"归档","value":"归档"}]',true);
        $nodes = $this->execNode($nodes);
        \app\admin\model\FlowGroup::saveGroup($data,$nodes);
        //添加第二个流程
        $json2 = json_decode('{"data":{"type":"项目管理类","code":"LCBH63A1492908109","name":"新项目流程6","desc":"aaaa","relate_name":"测试项目","relate_model":"app\\\admin\\\model\\\NewProject","status":1,"create_time":"2022-12-20 13:35:50","update_time":"2022-12-20 13:44:45","delete_time":null},"nodes":[{"id":"发布","flow_group_id":10,"name":"发布","next_id":["流程1"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":1,"level":1,"create_time":"2022-12-20 13:35:50","update_time":"2022-12-20 13:35:50","delete_time":null,"label":"发布","value":"发布"},{"id":"流程1","flow_group_id":10,"name":"流程1","next_id":["流程2","归档"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":11,"level":2,"create_time":"2022-12-20 13:35:50","update_time":"2022-12-20 13:35:50","delete_time":null,"label":"流程1","value":"流程1"},{"id":"流程2","flow_group_id":10,"name":"流程2","next_id":["归档"],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":21,"level":3,"create_time":"2022-12-20 13:35:50","update_time":"2022-12-20 13:35:50","delete_time":null,"label":"流程2","value":"流程2"},{"id":"归档","flow_group_id":10,"name":"归档","next_id":[],"depart_id":1,"role_id":1,"finish":0,"word_template":"","if_remark":0,"if_file":0,"sort":31,"level":4,"create_time":"2022-12-20 13:35:50","update_time":"2022-12-20 13:35:50","delete_time":null,"label":"归档","value":"归档"}]}',true);
        $json2['nodes'] = $this->execNode($json2['nodes']);
        \app\admin\model\FlowGroup::saveGroup($json2['data'],$json2['nodes']);
        //添加第一个流程项目
        \app\admin\model\Attend::create([
            'name'=>'投票活动',
            'user_id'=>1
        ]);
        //添加第二个流程项目
        app\admin\model\NewProject::create([
            'name'=>'关于2023年一建工程',
            'user_id'=>1
        ]);
    }

    public function execNode($nodes){
        $node_len = count($nodes);
        $sorts = [];
        foreach ($nodes as $k => &$node) {
            unset($node['id']);
            $node['level'] = $k + 1;
            if (!in_array($node['sort'], $sorts)) {
                $sorts[] = $node['sort'];
            } else {
                $node['sort'] = $node['sort'] + 1;
            }
        }
        $name_to_sort = collect($nodes)->column('sort', 'name');
        foreach ($nodes as $k => &$node) {
            if ($k < $node_len - 1) {
                if (count($node['next_id'])) {
                    $temp = [];
                    foreach ($node['next_id'] as $next) {
                        $temp[] = $name_to_sort[$next];
                    }
                    $node['next_id'] = $temp;
                } else {
                    $node['next_id'] = [$nodes[$k + 1]['sort']];
                }
            } else {
                $node['next_id'] = [0];
            }
        }
        return $nodes;
    }
}
