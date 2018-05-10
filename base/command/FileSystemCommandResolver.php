<?php
namespace base\command;

use base\http\Request;

class FileSystemCommandResolver implements CommandResolver {

    private $path;
    private $defaultCommand;

    public function __construct($path, $defaultCommand) {
        $this->path = $path;
        $this->defaultCommand = $defaultCommand;
    }

    public function getCommand(Request $request) {
        if($request->issetParamenterName('cmd')) {
            $cmdName = $request->getParameter('cmd');
            $command = $this->loadCommand($cmdName);
            if ($command instanceof Command) {
                return $command;
            }
        }
        $command = $this->loadCommand($this->defaultCommand);
        return $command;
    }

    protected function loadCommand($cmdName) {
        $cmdName = ucfirst($cmdName);
        $class = "Controller\\{$cmdName}Controller";
        $file = $this->path . "/{$cmdName}Controller.php";
        if (!file_exists($file)) {
            return false;
        }
        include_once $file;
        if (!class_exists($class)) {
            return false;
        }
        $command = new $class();
        return $command;
    }

    protected function loadApi($apiName) {
        $apiName = ucfirst($apiName);
        $class = "Controller\\Api\\{$apiName}Controller";
        $file = "controller/api/{$apiName}Controller.php";
        if (!file_exists($file)) {
            return false;
        }
        include_once $file;
        if (!class_exists($class)) {
            return false;
        }
        $command = new $class();
        return $command;
    }

}