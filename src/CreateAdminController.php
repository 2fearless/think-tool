<?php


namespace Fearless\ThinkTool;

//admin_controller创建
use think\facade\Console;
use think\facade\Db;
use think\helper\Str;

class CreateAdminController extends CommonScaffold
{
    public function create($table_name){
        $module = 'admin';
        $name = ucfirst(Str::camel($table_name));
        $path = root_path('app/'.$module.'/controller').$name.'.php';
        Console::call('make:model',[$module.'@'.$name]);
        if (!is_file($path)){
            $stub = file_get_contents($this->getStub());
            $prefix = config('database.connections')[config('database.default')]['prefix'];
            $full_name = $prefix.$table_name;
            $stub = $this
                ->replaceName($stub,$name)
                ->replaceField($stub,$full_name)
                ->fillMenu($stub,$full_name,$name)
                ->replaceSpace($stub);
            file_put_contents($path,$stub);
        }
        return $path;
    }
    /**
     * 替换名称.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceName(&$stub, string $name)
    {
        $stub = str_replace('Dummy',$name, $stub);
        return $this;
    }

    /**
     * 填充表单及验证
     * @param $stub
     * @param string $full_name
     * @return $this
     */
    protected function replaceField(&$stub, string $full_name)
    {
        $result = get_cols($full_name);
        $str = '';
        $valid = '';
        foreach ($result as $item){
            if ($item['pk']){
                $str .= '$this->addColumnId();'."\n";
            }else{
                if (!$item['comment']){
                    $item['comment'] = $item['field'];
                }
                if ($item['number']){
                    $str .= "\t\t\t".'$this->addColumn(\''.$item['field'].'\', \''.$item['comment'].'\')->sort()->add($require)->edit($require)->form_type_number()->form_width(2);'."\n";
                }else{
                    $str .= "\t\t\t".'$this->addColumn(\''.$item['field'].'\', \''.$item['comment'].'\')->add($require)->edit($require)->form_width(2);'."\n";
                }
                $valid .= "\t\t\t\t'".$item['field'].'|'.$item['comment']."' => 'require',"."\n";
            }
        }
        $stub = str_replace('Field', $str, $stub);
        $stub = str_replace('Valid', $valid, $stub);
        return $this;
    }

    /**
     * 填充菜单
     * @param $stub
     * @param string $full_name
     * @param $name
     * @return $this
     */
    protected function fillMenu(&$stub, string $full_name,$name)
    {
        $comment = rtrim(show_table_comment($full_name),'表');
        $str = "'name' => '{$comment}管理', 'type' => 0, 'icon' => 'layui-icon-tabs', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
            ['name' => '{$comment}列表', 'type' => 1, 'icon' => 'icon-md-apps', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'index', 'parameter' => '', 'status' => 1, 'isshow' => 1, 'sort' => 0, 'son' => [
                ['name' => '{$comment}添加', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'add', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '{$comment}编辑', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'edit', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '{$comment}删除', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'delete', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
                ['name' => '{$comment}详情', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'detail', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
//              ['name' => '{$comment}导入', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'import', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
//              ['name' => '{$comment}导出', 'type' => 0, 'icon' => '', 'app' => 'admin', 'controller' => '{$name}', 'action' => 'export', 'parameter' => '', 'status' => 0, 'isshow' => 0, 'sort' => 0],
        ]]";

        $stub = str_replace('FillMenu',$str, $stub);
        return $this;
    }
    /**
     * Replace spaces.
     *
     * @param string $stub
     *
     * @return mixed
     */
    public function replaceSpace($stub)
    {
        return str_replace(["\n\n\n", "\n    \n"], ["\n\n", ''], $stub);
    }
    /**
     * Get stub path of filter.
     *
     * @return string
     */
    public function getStub()
    {
        return __DIR__.'/controller.stub';
    }
}
