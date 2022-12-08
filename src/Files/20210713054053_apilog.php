<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Apilog extends Migrator
{
    public function change()
    {
        $table = $this->table('sys_apilog', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('接口请求日志表');

        $table->addColumn(Column::unsignedInteger('uid')->setDefault(0)->setComment('用户id'))
            ->addColumn(Column::tinyInteger('status')->setUnSigned()->setDefault(0)->setComment('0失败1成功'))
            ->addColumn(Column::string('info')->setDefault('')->setComment('请求方式'))
            ->addColumn(Column::string('get')->setDefault('')->setComment('请求地址'))
            ->addColumn(Column::text('param')->setNull(true)->setComment('请求参数'))
            ->addColumn(Column::text('echo')->setNull(true)->setComment('响应参数'))
            ->addColumn(Column::string('spent')->setDefault('')->setComment('请求时间'))
            ->addColumn(Column::timestamp('time')->setNullable())
            ->create();
    }
}
