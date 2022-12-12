<?php

use think\migration\Migrator;
use think\migration\db\Column;

//存在sql文件，迁移不生效
class Test extends Migrator
{
    public function change()
    {
        $table = $this->table('test_test', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('测试');

        $table->addColumn(Column::string('test')->setNullable()->setComment('测试')->setUnique())
            ->addColumn(Column::timestamp('create_time')->setNullable())
            ->addColumn(Column::timestamp('update_time')->setNullable())
            ->create();
    }
}
