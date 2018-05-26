<?php
namespace base\command;
/**
 * File System Command Resolver
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
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

}