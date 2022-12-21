<?php

use think\migration\Migrator;
use think\migration\db\Column;

class FlowGroup extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('flow_group', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('流程表');

        $table
            ->addColumn(Column::string('type')->setDefault('')->setComment('流程分类'))
            ->addColumn(Column::string('code')->setDefault('')->setComment('流程编号'))
            ->addColumn(Column::string('name')->setDefault('')->setComment('流程名称'))
            ->addColumn(Column::text('desc')->setNull(true)->setComment('流程描述'))
            ->addColumn(Column::string('relate_name')->setDefault('')->setComment('对应应用名称'))
            ->addColumn(Column::string('relate_model')->setDefault('')->setComment('对应应用模型'))
            ->addColumn(Column::tinyInteger('status')->setDefault(0)->setComment('0草稿/1启用/2弃用/3暂停'))
            ->addColumn(Column::timestamp('delete_time')->setNullable())
            ->addColumn(Column::timestamp('create_time')->setNullable())
            ->addColumn(Column::timestamp('update_time')->setNullable())
            ->create();
    }
}
