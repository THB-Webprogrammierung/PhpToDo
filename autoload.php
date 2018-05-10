<?php
spl_autoload_register(function($class_name) {
    $class = str_replace('\\', '/', $class_name);
    include $class . '.php';
});