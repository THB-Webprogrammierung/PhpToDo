<?php
namespace base\config;

class Registry {

    const KEY_CONFIGURATION = 'config';

    protected static $instance = null;
    protected $values = array();

    protected function __construct() { }

    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new Registry();
        }
        return self::$instance;
    }

    protected function set($key, $value) {
        $this->values[$key] = $value;
    }

    protected function get($key) {
        if(isset($this->values[$key])) {
            return $this->values[$key];
        }
        return null;
    }

    public function setConfiguration(Configuration $config) {
        $this->set(self::KEY_CONFIGURATION, $config);
    }

    public function getConfiguration() {
        return $this->get(self::KEY_CONFIGURATION);
    }

    private function __clone() { }

}