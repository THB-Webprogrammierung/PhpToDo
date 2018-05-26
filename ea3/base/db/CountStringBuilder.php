<?php
namespace base\db;
/**
 * CountStringBuilder
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
class CountStringBuilder {

    private $tableName;
    private $columnName;
    private $target;

    public function buildSqlString() {
        return "SELECT COUNT($this->columnName) FROM {$this->getTableName()} WHERE {$this->target}=:value";
    }

    public function setTableName($tableName) {
        if(is_string($tableName) && isset($tableName)) {
            $this->tableName = $tableName;
        }
    }

    private function getTableName() {
        return $this->tableName;
    }

    public function setColumnName($columnNames) {
        if(is_string($columnNames) && isset($columnNames)) {
            $this->columnName = $columnNames;
        }
    }

    private function getColumnName() {
        return $this->columnName;
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