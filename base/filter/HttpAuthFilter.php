<?php
namespace base\filter;

use base\http\Request;
use base\http\Response;
use base\config\Registry;
use model\User;

class HttpAuthFilter implements Filter {

    public function __construct() { }

    public function execute(Request $request, Response $response) {
        if($request->issetParamenterName('cmd') && $request->getParameter('cmd') != "registrieren") {

            if(!isset($_SESSION['login']) && $_SESSION['login'] != "ok") {
                $this->locationLogin();
            }

        }
    }

    private function locationLogin() {
        header('Location: '.$this->getDomain().'/');
        exit();
    }

    private function getDomain() {
        $reg = Registry::getInstance();
        return $reg->getConfiguration()->getDomain();
    }

}