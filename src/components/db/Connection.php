<?php


namespace semivori\yii\components\db;


class Connection extends \yii\db\Connection
{
    /**
     * Same with @see \yii\db\Connection::transaction
     * But rollback transaction if callback result === false
     *
     * @param  callable  $callback
     * @param  null  $isolationLevel
     * @return false|mixed
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function conditionalTransaction(callable $callback, $isolationLevel = null)
    {
        $transaction = $this->beginTransaction($isolationLevel);
        $level = $transaction->level;

        try {
            $result = call_user_func($callback, $this);
            if ($result === false) {
                $this->rollbackTransactionOnLevel($transaction, $level);
                return $result;
            }

            if ($transaction->isActive && $transaction->level === $level) {
                $transaction->commit();
            }
        } catch (\Exception $e) {
            $this->rollbackTransactionOnLevel($transaction, $level);
            throw $e;
        } catch (\Throwable $e) {
            $this->rollbackTransactionOnLevel($transaction, $level);
            throw $e;
        }

        return $result;
    }

    /**
     * @see \yii\db\Connection::rollbackTransactionOnLevel
     */
    private function rollbackTransactionOnLevel($transaction, $level)
    {
        if ($transaction->isActive && $transaction->level === $level) {
            // https://github.com/yiisoft/yii2/pull/13347
            try {
                $transaction->rollBack();
            } catch (\Exception $e) {
                \Yii::error($e, __METHOD__);

                // hide this exception to be able to continue throwing original exception outside
            }
        }
    }
}
