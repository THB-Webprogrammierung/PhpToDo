<?php
namespace base\db;
/**
 * InsertSqlStringBuilder
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
class InsertSqlStringBuilder {

    private $columnNames;
    private $placeholderNames;
    private $tableName;

    public function buildSqlString() {
        return "INSERT INTO {$this->getTableName()}({$this->getColumnNames()}) 
                VALUES({$this->getplaceholderNames()})";
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

    public function setPlaceholders($placeholderNames) {
        if(is_string($placeholderNames) && isset($placeholderNames)) {
            $this->placeholderNames = $placeholderNames;
        }
    }

    private function getplaceholderNames() {
        return $this->placeholderNames;
    }
}