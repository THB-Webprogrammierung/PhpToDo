<?php
namespace model;

use base\db\DatabaseModelOperations;
/**
 * Class Benutzer
 * @package model
 * @table=EA3_USER
 * @values=id,login,password
 */
class User extends DatabaseModelOperations {

    private $id, $login, $password;

    public function __construct() {
        parent::__construct();
    }

    public function getId() {
        if(!isset($this->id))
            return NULL;
        return $this->id;
    }

    protected function setId($id)  {
        if(is_int($id))
            $this->id = $id;
        else
            $this->id = (int) $id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = trim($login);
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = trim($password);
    }

}