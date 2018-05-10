<?php

namespace model;
/**
 * Class Benutzer
 * @package model
 * @table=todo
 * @values=id,owner,text,done
 */
class Todos extends Todo {

    private $todos = array();

    public function __construct() {
        parent::__construct();
    }

    public function setTodo($todo) {
        array_push($this->todos, $todo);
    }

    public function getTodo($id) {
        return $this->todos[$id];
    }

    public function getTodos($result) {
        foreach($result as $value) {
            $todo = new Todo();
            $todo->setId($value['id']);
            $todo->setOwner($value['owner']);
            $todo->setText($value['text']);
            $todo->setDone($value['done']);
            $this->setTodo($todo);
        }
        return $this->todos;
    }
}