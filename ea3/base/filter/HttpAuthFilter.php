<?php
namespace base\filter;
/**
 * HttpAuthFilter
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
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