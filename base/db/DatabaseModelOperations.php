<?php
namespace base\db;

use PDO;

class DatabaseModelOperations extends DatabaseConnect {
    /**
     * @var string $strTableName
     * Speicher für den Tabellennamen
     */
    private $strTableName;
    /**
     * @var array $arrColumNames
     * Speicher für die Spalten der Tabelle
     */
    private $arrColumNames;
    /**
     * @var object $dbc
     * Speicher für das Dantenbank Verbindungs Object
     */
    private $dbc;
    /**
     * @var object $rc
     * Speicher für das ReflectionClass Object
     */
    private $rc;
    /**
     * DatabaseModelOperations constructor.
     * @throws \ReflectionException
     * Extrahiert die in den Kommentaren hinterlegten Werte der
     * Datenbanktabelle und Spalten des aufrufenden Datenmodells.
     */
    public function __construct() {
        $this->rc = new \ReflectionClass(get_called_class());
        $this->strTableName = $this->getTable();
        $this->arrColumNames = $this->getColumnNames();
    }
    /**
     * startConnection
     * Stellt die Verbindung zur Datenbank her
     */
    private function startConnection() {
        $this->db = parent::getInstance();
        $this->dbc = $this->db->connect();
    }
    /**
     * closeConnection
     * Schließt die Verbindung zur Datenbank
     */
    private function closeConnection() {
        $this->db = null;
    }
    /**
     * getTable
     * @param $rc \ReflectionClass
     * @return string Tabellenname
     * Extrakiert den Tabellennamen @table aus dem Kommentar
     * des aufrufenden Datenmodells
     */
    private function getTable() {
        preg_match('/@table=(.*?)/U', $this->rc->getDocComment(), $table);
        return $table[1];
    }
    /**
     * getColumnNames
     * @param $rc \ReflectionClass
     * @return array Spaltennamen
     * Extrahiert die Spatennamen @values aus dem Kommentar
     * des aufrufenden Datenmodells
     */
    private function getColumnNames() {
        preg_match('/@values=(.*?)/U', $this->rc->getDocComment(), $values);
        $arrColumnNames = explode(",", $values[1]);
        return $arrColumnNames;
    }
    /**
     * getColumnNamesAsString
     * @return string Spalennamen
     * Gibt die im Datenmodell angegebenen Spaltennamen der Tabelle zurück.
     */
    private function getColumnNamesAsString() {
        $dbPreparedData = "";
        for($i=0; $i<count($this->arrColumNames); $i++) {
            $dbPreparedData .= trim($this->arrColumNames[$i]).",";
        }
        $dbPreparedData = substr($dbPreparedData, 0, -1);
        return $dbPreparedData;
    }
    /**
     * getColumnNamesAsString
     * @return string $values
     * Gibt einen kommaseparierten String mit den Spaltennamen
     * des Datenmodells zurück, deren Werte gesetzt sind
     */
    private function getFilteredColumnNamesAsString() {
        $dbPreparedData = "";
        for($i=0; $i<count($this->arrColumNames); $i++) {
            $getterName = trim("get".ucfirst($this->arrColumNames[$i]));
            if(!$this->$getterName() == null || $this->$getterName() != '') {
                $dbPreparedData .= trim($this->arrColumNames[$i]).",";
            }
        }
        $dbPreparedData = substr($dbPreparedData, 0, -1);
        return $dbPreparedData;
    }
    /**
     * getPlaceholdersAsString
     * @return string Placeholders
     * Gibt die Platzhalter für den 'prepared' SQL String zurück:
     * kommasepariert, nur Platzhalter von im Datenmdell belegten Werten
     */
    private function getPlaceholdersAsString() {
        $dbPreparedData = "";
        for($i=0; $i<count($this->arrColumNames); $i++) {
            $getterName = trim("get".ucfirst($this->arrColumNames[$i]));
            if(!$this->$getterName() == null || $this->$getterName() != '') {
                $dbPreparedData .= ":".trim($this->arrColumNames[$i]).",";
            }
        }
        $dbPreparedData = substr($dbPreparedData, 0, -1);
        return $dbPreparedData;
    }
    /**
     * getValuesAsArray
     * @return array Values
     * Schreibt die Platzhalter mit Wert in ein Array
     */
    private function getValuesAsArray() {
        $dbPreparedData = array();
        for($i=0; $i<count($this->arrColumNames); $i++) {
            $getterName = trim("get".ucfirst($this->arrColumNames[$i]));
            if($this->$getterName() != null || $this->$getterName() != '') {
                $dbPreparedData[":".trim($this->arrColumNames[$i])] = $this->$getterName();
            }
        }
        return $dbPreparedData;
    }
    /**
     * getUpdateString
     * @return string UpdateString
     * Gibt den aus Spaltennamen und Platzhaltern bestehenden String
     * für ein SQL Update zurück.
     */
    private function getUpdateString() {
        $dbPreparedData = "";
        for($i=0; $i<count($this->arrColumNames); $i++) {
            $dbPreparedData .= $this->arrColumNames[$i]."=:".trim($this->arrColumNames[$i]).",";
        }
        $dbPreparedData = substr($dbPreparedData, 0, -1);
        return $dbPreparedData;
    }
    /**
     * SQL Insert
     * @return bool|int
     * Fügt in die Tabelle alle erlaubten und im Modell gesetzten
     * Werte ein.
     */
    public function insert() {
        $this->startConnection();

        $sqlStringBuilder = new InsertSqlStringBuilder();
        $sqlStringBuilder->setTableName($this->strTableName);
        $sqlStringBuilder->setColumnNames($this->getFilteredColumnNamesAsString());
        $sqlStringBuilder->setPlaceholders($this->getPlaceholdersAsString());

        $sth = $this->dbc->prepare($sqlStringBuilder->buildSqlString(), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if($sth->execute($this->getValuesAsArray())) {
            return true;
        } else if ($sth->errorCode()) {
            return $sth->errorCode();
        }

        $this->closeConnection();
    }
    /**
     * SQL Update
     * @return bool|int
     * Führt ein Update der Daten des Datenmodells durch
     */
    public function save() {
        $this->startConnection();

        $sqlStringBuilder = new UpdateSqlStringBuilder();
        $sqlStringBuilder->setTableName($this->strTableName);
        $sqlStringBuilder->setUpdateString($this->getUpdateString());
        $sqlStringBuilder->setTarget($this->getId());

        $sth = $this->dbc->prepare($sqlStringBuilder->buildSqlString(), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        if($sth->execute($this->getValuesAsArray())) {
            return true;
        } else if ($sth->errorCode()) {
            return $sth->errorCode();
        }

        $this->closeConnection();
    }
    /**
     * SQL Select
     * @param $name Spaltenname
     * @param $value Suchwert
     * @return mixed
     * Sucht nach einem bestimmten Wert und schreibt das
     * Ergebnis in das Datenmodell
     */
    public function findOne($name, $value) {
        $this->startConnection();

        $sqlStringBuilder = new SelectSqlStringBuilder();
        $sqlStringBuilder->setTableName($this->strTableName);
        $sqlStringBuilder->setColumnNames($this->getColumnNamesAsString());
        $sqlStringBuilder->setTarget($name);

        $sth = $this->dbc->prepare($sqlStringBuilder->buildSqlString());

        $sth->execute(array(':value' => $value));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if($result) {
            foreach($result as $key => $value) {
                $settername = 'set'.ucfirst($key);
                $this->$settername($value);
            }
        } else
            return $sth->errorCode();

        $this->closeConnection();
    }

    public function find($name, $value) {
        $this->startConnection();

        $sqlStringBuilder = new SelectSqlStringBuilder();
        $sqlStringBuilder->setTableName($this->strTableName);
        $sqlStringBuilder->setColumnNames($this->getColumnNamesAsString());
        $sqlStringBuilder->setTarget($name);

        $sth = $this->dbc->prepare($sqlStringBuilder->buildSqlString());

        $sth->execute(array(':value' => $value));
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($result) {
            return $result;
        } else
            return $sth->errorCode();

        $this->closeConnection();
    }
    /**
     * SQL Count
     */
    public function count($columnName, $value) {
        $this->startConnection();

        $sqlStringBuilder = new CountStringBuilder();
        $sqlStringBuilder->setTableName($this->strTableName);
        $sqlStringBuilder->setColumnName($columnName);
        $sqlStringBuilder->setTarget($columnName);

        $sth = $this->dbc->prepare($sqlStringBuilder->buildSqlString());

        $sth->execute(array(':value' => $value));
        $result = $sth->fetchColumn();
        if($result) {
            return $result;
        } else
            return $sth->errorCode();

        $this->closeConnection();
    }

    /**
     * SQL Delete
     */
    public function delete() {
        $this->startConnection();

        $sqlStringBuilder = new DeleteSqlStringBuilder();
        $sqlStringBuilder->setTableName($this->strTableName);

        $sth = $this->dbc->prepare($sqlStringBuilder->buildSqlString());

        $sth->execute(array(':value' => $this->getId()));

        $this->closeConnection();
    }
}