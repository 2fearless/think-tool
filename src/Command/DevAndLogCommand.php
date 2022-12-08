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
        $this->dumpMigrate();
        $this->dumpAdminController();
        $this->dumpModel();
        $this->dumpView();
        $this->replaceErr();
        $this->buryApiLog();
        Console::call('migrate:run');
        Console::call('hw:um');
        $output->writeln('complete');
    }

    public function dumpMigrate(){
        $apilog_mig = app()->getAppPath().'..'.DIRECTORY_SEPARATOR.'database'
            .DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR.'20210713054053_apilog.php';

        if (is_file($apilog_mig)) {
            return;
        }
        copy(__DIR__.DIRECTORY_SEPARATOR.'..'
            .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
            .'20210713054053_apilog.php', $apilog_mig);
    }

    public function dumpAdminController(){
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
    }

    public function dumpModel(){
        $apilog_model = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'SysApilog.php';
        if (!is_file($apilog_model)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'SysApilog.php', $apilog_model);
        }
    }

    public function dumpView(){
        $dir_path = app()->getAppPath().DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'mc';
        if (!is_dir($dir_path)){
            mkdir($dir_path);
        }
        $mc_view =$dir_path.DIRECTORY_SEPARATOR.'index.html';
        if (!is_file($mc_view)) {
            copy(__DIR__.DIRECTORY_SEPARATOR.'..'
                .DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR
                .'index.html', $mc_view);
        }
    }

    public function replaceErr(){
        $str = '        if ($e->getMessage() && strtolower(app("http")->getName()) != "admin"){
            return json_error($e->getMessage(),"");
        }';
        $file = app()->getAppPath().DIRECTORY_SEPARATOR.'ExceptionHandle.php';
        $content = file_get_contents($file);
        $done = \think\helper\Str::contains($content,'return json_error($e->getMessage()');
        if ($done){
            return;
        }
        $content = str_replace('// 添加自定义异常处理机制',"// 添加自定义异常处理机制'\r\n".$str,$content);
        file_put_contents($file,$content);
    }

    public function buryApiLog(){
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
        $done = \think\helper\Str::contains($append,'function end');
        if ($done){
            return;
        }
        $content = substr($content,0,strlen($content)-3).$append;
        file_put_contents($file,$content);
    }
}
