<?php
namespace controller;

use base\command\Command;
use base\http\Request;
use base\http\Response;
use base\view\HtmlTemplateView;
use base\config\Registry;
use model\User;

class IndexController implements Command {

    public function execute(Request $request, Response $response) {
        /* Konfigurationsvariablen */
        $reg = Registry::getInstance();

        $authenticationSuccess = false;
        $meldung = "";

        if($request->getRequestMethod() == 'GET' && isset($_GET['logout']) && $_GET['logout'] == "true") {
            session_unset();
        }

        if(isset($_SESSION['login']) && $_SESSION['login'] == "ok") {
            header('Location: '.$reg->getConfiguration()->getDomain().'/aufgabenliste/');
            exit();
        }

        if($request->getRequestMethod() == 'POST') {
            if(isset($_POST['login']) && isset($_POST['password'])) {
                $user = new User();
                $user->findOne("login", $_POST['login']);
                if($user->getId() > -1) {
                    if(password_verify($_POST['password'], $user->getPassword())) {
                        $authenticationSuccess = true;
                    }
                }
            }
            if($authenticationSuccess === true) {
                $_SESSION['login'] = 'ok';
                $_SESSION['name'] = $user->getLogin();
                header('Location: '.$reg->getConfiguration()->getDomain().'/aufgabenliste/');
                exit();
            } else {
                $meldung = "Sie haben ungültige Zugangsdaten eingegeben. Bitte korrigieren Sie diese!";
            }
        }


        /* Template initialisieren */
        $view = new HtmlTemplateView('login');
        /* Dem Template die nötigen Daten zuweisen */
        $view->assign('domain', $reg->getConfiguration()->getDomain());
        $view->assign('seite', "Todolist - Login");
        $view->assign('meldung', $meldung);
        /* Die View rendern */
        $view->render($request, $response);


    }
}