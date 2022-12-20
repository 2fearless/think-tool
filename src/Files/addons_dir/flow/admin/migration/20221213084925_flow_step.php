<?php

use think\migration\Migrator;
use think\migration\db\Column;

class FlowStep extends Migrator
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
        $table = $this->table('flow_step', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('执行步骤');

        $table
            ->addColumn(Column::string('remark')->setComment('操作描述'))
            ->addColumn(Column::text('file')->setNull(true)->setComment('文件'))
            ->addColumn(Column::integer('flow_group_id')->setComment('流程id'))
            ->addColumn(Column::integer('flow_project_id')->setComment('流程项目id'))
            ->addColumn(Column::integer('flow_node_id')->setComment('当前进度'))
            ->addColumn(Column::tinyInteger('finish_flag')->setComment('完成标识'))
            ->addColumn(Column::string('next_flow_node_id')->setComment('下一进度'))
            ->addColumn(Column::integer('sort')->setComment('步骤'))
            ->addColumn(Column::integer('level')->setComment('层级'))
            ->addColumn(Column::integer('user_id')->setComment('用户id'))
            ->addColumn(Column::timestamp('create_time')->setNullable())
            ->addColumn(Column::timestamp('update_time')->setNullable())
            ->create();
    }
}
