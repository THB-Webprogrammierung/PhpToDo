<?php
namespace model;

use base\db\DatabaseModelOperations;
/**
 * Class Benutzer
 * @package model
 * @table=user
 * @values=id,login,password
 */
class User extends DatabaseModelOperations {

    private $id, $login, $password;

    public function __construct() {
        parent::__construct();
    }
    /**
     * @return int $id
     */
    public function getId() {
        if(!isset($this->id))
            return NULL;
        return $this->id;
    }
    /**
     * @param int $id
     */
    protected function setId($id)  {
        if(is_int($id))
            $this->id = $id;
        else
            $this->id = (int) $id;
    }
    /**
     * @return string $login
     */
    public function getLogin() {
        return $this->login;
    }
    /**
     * @param string $benutzername
     */
    public function setLogin($login) {
        $this->login = trim($login);
    }
    /**
     * @return string $passwort
     */
    public function getPassword() {
        return $this->password;
    }
    /**
     * @param string $passwort
     */
    public function setPassword($password) {
        $this->password = trim($password);
    }

}