<?php


namespace components\db\mysql;


class Command extends \yii\db\Command
{
    public function batchInsertUpdate($table, $columns, $rows, $update)
    {
        $sql = $this->db
            ->queryBuilder
            ->batchInsert($table, $columns, $rows);

        $sql .= " ON DUPLICATE KEY UPDATE";

        foreach ($update as $field) {
            $sql .= " `$field` = VALUES(`$field`)";
        }
        return $this->db->createCommand($sql);
    }
}
