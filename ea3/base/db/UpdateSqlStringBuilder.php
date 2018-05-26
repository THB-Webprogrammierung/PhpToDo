<?php
namespace base\db;
/**
 * UpdateSqlStringBuilder
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
class UpdateSqlStringBuilder {

    private $tableName;
    private $updateString;
    private $target;

    public function buildSqlString() {
        return "UPDATE {$this->getTableName()}
                SET {$this->getUpdateString()}
                WHERE id={$this->getTarget()}";
    }

    public function setTableName($tableName) {
        if(is_string($tableName) && isset($tableName)) {
            $this->tableName = $tableName;
        }
    }

    private function getTableName() {
        return $this->tableName;
    }

    public function setUpdateString($updateString) {
        if(is_string($updateString) && isset($updateString)) {
            $this->updateString = $updateString;
        }
    }

    private function getUpdateString() {
        return $this->updateString;
    }

    public function setTarget($target) {
        if(isset($target)) {
            $this->target = $target;
        }
    }

    private function getTarget() {
        return $this->target;
    }
}