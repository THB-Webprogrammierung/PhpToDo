<?php
namespace base\db;

use PDO;
use PDOException;
use base\config\Registry;

abstract class DatabaseConnect {

    // Initialisierung der Instanz
    private static $instances = array();
    private $dbh;

    // Verhindern, dass eine zweite Instanz des Objektes erzeugt werden kann
    protected function __construct() {}

    // Methode zur Erzeugung bzw. dem Aufruf der Instanz
    final public static function getInstance() {
        $classname = get_called_class();
        if (!isset($instances[$classname])) {
            $instances[$classname] = new $classname();
        }
        return $instances[$classname];
    }

    // Verhindern, dass die Klasse geklont wird
    final private function __clone() { }

    // Klassenmethoden
    final public static function connect() {
        $reg = Registry::getInstance();
        try {
            $dbh = new PDO('mysql:dbname='.$reg->getConfiguration()->getDatabase().';host='.$reg->getConfiguration()->getHost().'',
                $reg->getConfiguration()->getUser(),
                $reg->getConfiguration()->getPass(),
                array(PDO::MYSQL_ATTR_FOUND_ROWS => true)
            );
            return $dbh;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            die();
        }
    }

}