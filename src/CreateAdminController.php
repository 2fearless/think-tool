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
        $path = app_path($module.'/controller').$name.'.php';
        Console::call('make:model',[$module.'@'.$name]);
        if (!is_file($path)){
            $stub = file_get_contents($this->getStub());
            $prefix = config('database.connections')[config('database.default')]['prefix'];
            $full_name = $prefix.$table_name;
            $stub = $this
                ->replaceName($stub,$name)
                ->replaceField($stub,$full_name)
                ->replaceSpace($stub);
            file_put_contents($path,$stub);
        }
        return $path;
    }
    /**
     * Replace namespace dummy.
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

    protected function replaceField(&$stub, string $full_name)
    {
        $result = get_cols($full_name);
        $str = '';
        $valid = '';
        foreach ($result as $item){
            if ($item['pk']){
                $str .= '$this->addColumnId();'."\n";
            }else{
                if ($item['number']){
                    $str .= "\t\t\t".'$this->addColumn(\''.$item['field'].'\', \''.$item['comment'].'\')->sort()->add($require)->edit($require)->form_type_number()->form_width(2);'."\n";
                }else{
                    $str .= "\t\t\t".'$this->addColumn(\''.$item['field'].'\', \''.$item['comment'].'\')->add($require)->edit($require)->form_width(2);'."\n";
                }
                $valid .= "\t\t\t'".$item['field'].'|'.$item['comment']."' => 'require',"."\n";
            }
        }
        $stub = str_replace('Field', $str, $stub);
        $stub = str_replace('Valid', $valid, $stub);
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