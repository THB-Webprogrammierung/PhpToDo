<?php
namespace base\db;


class SelectSqlStringBuilder {

    private $tableName;
    private $columnNames;
    private $target;

    public function buildSqlString() {
        return "SELECT {$this->getColumnNames()} FROM {$this->getTableName()} WHERE {$this->target}=:value";
    }

    public function setTableName($tableName) {
        if(is_string($tableName) && isset($tableName)) {
            $this->tableName = $tableName;
        }
    }

    private function getTableName() {
        return $this->tableName;
    }

    public function setColumnNames($columnNames) {
        if(is_string($columnNames) && isset($columnNames)) {
            $this->columnNames = $columnNames;
        }
    }

    private function getColumnNames() {
        return $this->columnNames;
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