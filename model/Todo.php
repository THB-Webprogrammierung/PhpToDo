<?php
namespace model;
use base\db\DatabaseModelOperations;
/**
 * Class Benutzer
 * @package model
 * @table=todo
 * @values=id,owner,text,done
 */

class Todo extends DatabaseModelOperations {

    private $id, $owner, $text, $done;

    public function __construct() {
        parent::__construct();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setOwner($owner)  {
        $this->owner = $owner;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getDone() {
        return $this->done;
    }

    public function setDone($done) {
        $this->done = $done;
    }

}