<?php
namespace controller;

use base\command\Command;
use base\http\Request;
use base\http\Response;
use base\view\HtmlTemplateView;
use base\config\Registry;
use model\User;

class RegistrierenController implements Command {

    public function execute(Request $request, Response $response) {
        /* Deklarationen */
        $meldung = "";
        /* Konfigurationsvariablen */
        $reg = Registry::getInstance();
        /* Benutzeranmeldung: Request Methode ist POST */
        if($request->getRequestMethod() == 'POST') {
            /* Überprüfen der übermittelten Daten auf Vollständigkeit */
            if($request->getParameter('login') != "" && $request->getParameter('password') != "" && $request->getParameter('passwordrepeat') != "") {
                if($request->getParameter('password') != $request->getParameter('passwordrepeat')) {
                    $meldung = "Die Passwörter stimmen nicht überein!";
                } else {
                    /* Wenn alles richtig ist, dann wird der Nutzer in der Datenbank angelegt */
                    $user = new User();
                    /* Prüfung, ob der Nutzername schon vergeben ist */
                    $user->findOne('login', $request->getParameter('login'));
                    if($user->getId() > -1) {
                        print $user->findOne('login', $request->getParameter('login'));
                        $meldung = "Dieser Benutzername ist bereits vergeben!";
                    } else {
                        $user->setLogin($request->getParameter('login'));
                        $user->setPassword(password_hash($request->getParameter('password'), PASSWORD_BCRYPT));
                        $user->insert();
                        $meldung = "Sie haben sich erfolgreich registiert! <a href=\"{$reg->getConfiguration()->getDomain()}\">Bitte loggen Sie sich hier ein.</a>";
                        $request->deleteParameter('login');
                        $request->deleteParameter('password');
                        $request->deleteParameter('passwordrepeat');
                    }
                }
            } else {
                $meldung = "Bitte füllen Sie alle Felder aus, um sich zu registrieren!";
            }
        }
        /* Template initialisieren */
        $view = new HtmlTemplateView('registrieren');
        /* Dem Template die nötigen Daten zuweisen */
        $view->assign('domain', $reg->getConfiguration()->getDomain());
        $view->assign('seite', "Todolist - Registrierung");
        $view->assign('meldung', $meldung);
        $view->assign('login', $request->getParameter('login'));
        $view->assign('password', $request->getParameter('password'));
        /* Die View rendern */
        $view->render($request, $response);
    }
}