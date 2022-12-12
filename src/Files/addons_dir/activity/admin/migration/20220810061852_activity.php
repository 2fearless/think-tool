<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Activity extends Migrator
{
    public function change()
    {
        $table = $this->table('activity', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('活动表');

        $table
            ->addColumn(Column::integer('sort')->setDefault(0)->setComment('排序'))
            ->addColumn(Column::string('name')->setNullable()->setComment('活动名称'))
            ->addColumn(Column::string('images')->setNullable()->setComment('活动图片'))
            ->addColumn(Column::text('content')->setNullable()->setComment('活动内容'))
            ->addColumn(Column::timestamp('create_time')->setNullable())
            ->addColumn(Column::timestamp('update_time')->setNullable())
            ->create();
    }
}
