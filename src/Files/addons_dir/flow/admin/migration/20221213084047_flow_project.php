<?php

use think\migration\Migrator;
use think\migration\db\Column;

class FlowProject extends Migrator
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
        $table = $this->table('flow_project', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('项目表');

        $table
            ->addColumn(Column::integer('relate_id')->setDefault(0)->setComment('关联模型id'))
            ->addColumn(Column::string('relate_model')->setDefault('')->setComment('关联模型名称'))
            ->addColumn(Column::integer('flow_group_id')->setDefault(0)->setComment('流程id'))
            ->addColumn(Column::integer('flow_node_id')->setDefault(0)->setComment('流程进度'))
            ->addColumn(Column::integer('flow_step_id')->setDefault(0)->setComment('当前进度'))
            ->addColumn(Column::integer('user_id')->setDefault(0)->setComment('用户id'))
            ->addColumn(Column::timestamp('create_time')->setNullable())
            ->addColumn(Column::timestamp('update_time')->setNullable())
            ->create();
    }
}
