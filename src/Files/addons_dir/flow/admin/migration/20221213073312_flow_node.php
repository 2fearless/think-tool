<?php

use think\migration\Migrator;
use think\migration\db\Column;

class FlowNode extends Migrator
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
        $table = $this->table('flow_node', ['signed' => false])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_unicode_ci')
            ->setComment('流程节点表');

        $table
            ->addColumn(Column::integer('flow_group_id')->setDefault(0)->setComment('流程id'))
            ->addColumn(Column::string('name')->setDefault('')->setComment('节点名称'))
            ->addColumn(Column::string('next_id')->setDefault(0)->setComment('下一步id'))
            ->addColumn(Column::integer('depart_id')->setDefault(0)->setComment('部门id'))
            ->addColumn(Column::integer('role_id')->setDefault(0)->setComment('角色id'))
            ->addColumn(Column::tinyInteger('finish')->setDefault(0)->setComment('是否可以直接完成'))
            ->addColumn(Column::text('word_template')->setNull(true)->setComment('话术模板'))
            ->addColumn(Column::tinyInteger('if_remark')->setDefault(1)->setComment('是否开启备注'))
            ->addColumn(Column::tinyInteger('if_file')->setDefault(0)->setComment('是否上传文件'))
            ->addColumn(Column::integer('sort')->setDefault(0)->setComment('流程排序'))
            ->addColumn(Column::integer('level')->setDefault(0)->setComment('流程层级'))

            ->addColumn(Column::timestamp('create_time')->setNullable())
            ->addColumn(Column::timestamp('update_time')->setNullable())
            ->create();
    }
}
