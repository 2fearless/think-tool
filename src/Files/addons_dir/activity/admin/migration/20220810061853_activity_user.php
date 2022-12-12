<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ActivityUser extends Migrator
{
    public function change()
    {
        $table = $this->table('activity_user', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('活动报名用户');

        $table
            ->addColumn(Column::integer('user_id')->setDefault(0)->setComment('用户id'))
            ->addColumn(Column::integer('activity_id')->setDefault(0)->setComment('活动id'))
            ->addColumn(Column::timestamp('create_time')->setNullable())
            ->addColumn(Column::timestamp('update_time')->setNullable())
            ->create();
    }
}
