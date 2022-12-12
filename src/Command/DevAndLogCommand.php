<?php


namespace Fearless\ThinkTool\Command;

use think\console\Input;
use think\console\Output;
use think\facade\App;
use think\facade\Console;

class DevAndLogCommand extends \think\console\Command
{
    public function configure()
    {
        $this->setName('hw:dl')
            ->setDescription('创建后台开发管理');
    }

    public function execute(Input $input, Output $output)
    {
        $this->dumpMigrate($output);
        $this->dumpAdminController($output);
        $this->dumpModel($output);
        $this->dumpView($output);
        $this->replaceErr($output);
        $this->buryApiLog($output);
        Console::call('migrate:run');
        Console::call('hw:addons');
        Console::call('hw:um');
        $output->writeln('complete');
    }

    public function dumpMigrate($output){
        $apilog_mig = app()->getAppPath().'..'.DIRECTORY_SEPARATOR.'database'
            .DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'20210713054053_apilog.php';

        if (is_file($apilog_mig)) {
            return;
        }
        copy(__DIR__.DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
            .'20210713054053_apilog.php', $apilog_mig);
        $output->writeln('完成生成迁移文件'.$apilog_mig);
    }

    public function dumpAdminController($output){
        $apilog_con = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'sys'.DIRECTORY_SEPARATOR.'Apilog.php';
        if (!is_file($apilog_con)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'Apilog.php', $apilog_con);
        }

        $mc_con = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'Mc.php';
        if (!is_file($mc_con)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'Mc.php', $mc_con);
        }
        $addons_con = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'Addons.php';
        if (!is_file($addons_con)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'Addons.php', $addons_con);
        }
        $output->writeln('完成生成后台控制器'.$apilog_con);
        $output->writeln('完成生成后台控制器'.$mc_con);
        $output->writeln('完成生成后台控制器'.$addons_con);
    }

    public function dumpModel($output){
        $apilog_model = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'SysApilog.php';
        if (!is_file($apilog_model)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'SysApilog.php', $apilog_model);
        }
        $output->writeln('完成生成模型'.$apilog_model);
    }

    public function dumpView($output){
        $dir_path = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'mc';
        if (!is_dir($dir_path)){
            mkdir($dir_path);
        }
        $mc_view = $dir_path.DIRECTORY_SEPARATOR.'index.html';
        if (!is_file($mc_view)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'index.html', $mc_view);
        }

        $addons_view_dir_path = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'addons';
        if (!is_dir($addons_view_dir_path)){
            mkdir($addons_view_dir_path);
        }
        $addons_view = $addons_view_dir_path.DIRECTORY_SEPARATOR.'index.html';
        if (!is_file($addons_view)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'addons_view'.DIRECTORY_SEPARATOR.'index.html', $addons_view);
        }
        $output->writeln('完成生成视图'.$addons_view);
    }

    public function replaceErr($output){
        $str = '        if ($e->getMessage() && strtolower(app("http")->getName()) != "admin"){
            return json_error($e->getMessage(),"");
        }';
        $file = app()->getAppPath().DIRECTORY_SEPARATOR.'ExceptionHandle.php';
        $content = file_get_contents($file);
        $done = \think\helper\Str::contains($content,'return json_error($e->getMessage()');
        if ($done){
            return;
        }
        $content2 = str_replace('// 添加自定义异常处理机制',"// 添加自定义异常处理机制".PHP_EOL.$str,$content);
        file_put_contents($file,$content2);
        $output->writeln('完成修改异常提示文件'.$file);
    }

    public function buryApiLog($output){
        $append = '    public function end(\think\Response $response){
        $request = \request();
        $start = $request->server("REQUEST_TIME_FLOAT");
        $end = microtime(true);
        $data = [
            "info" => $request->method(),
            "uid" => api_id() ?? 0,
            "status" => json_decode($response->getContent(),true)["code"],
            "get" => $request->url(true),
            "param"=> json_encode($request->param(), JSON_UNESCAPED_UNICODE),
            "echo" => $response->getContent(),
            "spent" => round($end-$start,3),
        ];
        \app\admin\model\SysApilog::create($data);
    }
}';
        $file = app()->getAppPath().DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'middleware'.DIRECTORY_SEPARATOR.'CheckToken.php';
        $content = file_get_contents($file);
        $done = \think\helper\Str::contains($content,'SysApilog');
        if ($done){
            return;
        }
        $content2 = substr($content,0,strlen($content)-3).PHP_EOL.$append;
        file_put_contents($file,$content2);
        $output->writeln('完成中间件埋点'.$file);
    }
}
