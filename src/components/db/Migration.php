<?php


namespace components\db;


use yii\base\NotSupportedException;

class Migration extends \yii\db\Migration
{
    /**
     * @return \yii\db\ColumnSchemaBuilder
     */
    public function foreignId()
    {
        return $this->integer()->unsigned();
    }

    /**
     * @inheritdoc
     */
    public function primaryKey($length = null)
    {
        return parent::primaryKey($length)->unsigned();
    }

    /**
     * @param $values
     * @return \yii\db\ColumnSchemaBuilder
     * @throws NotSupportedException|\yii\base\NotSupportedException
     */
    public function enum($values)
    {
        if ($this->db->getDriverName() != 'mysql') {
            throw new NotSupportedException("ENUM type is only supported in MySQL");
        }

        $values = implode(
            ', ',
            array_map(
                function ($value) {
                    return "'{$value}'";
                },
                array_filter(
                    array_map(
                        function ($value) {
                            return (string)$value;
                        },
                        (array)$values
                    ),
                    function ($value) {
                        return !empty($value);
                    }
                )
            )
        );

        $column = "ENUM({$values})";

        return $this->getDb()
            ->getSchema()
            ->createColumnSchemaBuilder($column);
    }
}
