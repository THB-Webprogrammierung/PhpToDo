<?php
namespace base\config;
/**
 * Configuration
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
class Configuration {

    private $domain;
    private $rootDirectory;
    private $host;
    private $user;
    private $pass;
    private $database;

    public function __construct($domain, $rootDirectory, $host, $user, $pass, $database) {
        $this->domain = $domain;
        $this->rootDirectory = $rootDirectory;
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
    }

    public function getHost() {
        return $this->host;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function getRootDirectory() {
        return $this->rootDirectory;
    }

    public function getDomain() {
        return $this->domain;
    }

}