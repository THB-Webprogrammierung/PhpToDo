<?php
namespace controller;
/**
 * Registrieren Controller
 *
 * Controller für die Seite 'Registrieren'
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
use base\command\Command;
use base\http\Request;
use base\http\Response;
use base\view\HtmlTemplateView;
use base\config\Registry;
use model\User;

class RegistrierenController implements Command {
    /**
     * Execute
     *
     * Methode, die die serverseitige Bearbeitung realisiert. Verbindet das Datenschicht mit der Ausgabeschicht.
     *
     * @param Request $request
     * @param Response $response
     * @access public
     */
    public function execute(Request $request, Response $response) {
        /* Deklarationen */
        $meldung = "";
        /* Initialisierung der String Variablen für eine etwaige Meldung, wie zum Beispiel 'Login war nicht erfolgreich' */
        $reg = Registry::getInstance();
        /* Benutzeranmeldung: Request Methode ist POST */
        if($request->getRequestMethod() == 'POST') {
            /* Überprüfen der übermittelten Daten auf Vollständigkeit und Korrektheit (Passwörter stimmen überein) */
            if($request->getParameter('login') != "" && $request->getParameter('password') != "" && $request->getParameter('passwordrepeat') != "") {
                if($request->getParameter('password') != $request->getParameter('passwordrepeat')) {
                    /* Wenn die Passwörter nicht übereinstimmen, dann wird folgende Meldung ausgegeben: */
                    $meldung = "Die Passwörter stimmen nicht überein!";
                    /* Prüfen, ob der Benutzername den Voprgaben entspricht: */
                } else if(!$this->checkUser($request->getParameter('login'))) {
                    $meldung = "Der Nutzername darf nur aus Buchstaben, Zahlen und Unterstrich bestehen! " . $this->checkUser($request->getParameter('login'));
                    /* Prüfen, ob das Passwort den Vorgaben entspricht: */
                } else if(!$this->checkPassword($request->getParameter('password'))) {
                    $meldung = "Das Passwort muss mindestens 6 Zeichen lang sein, einen Buchstaben, eine Zahl und eines der folgenden Sonderzeichen enthalten: .,#+!$";
                } else {
                    /* Wenn alles richtig ist, dann wird der Nutzer in der Datenbank angelegt */
                    $user = new User();
                    /* Prüfung, ob der Nutzername schon vergeben ist */
                    $user->findOne('login', $request->getParameter('login'));
                    if($user->getId() > -1) {
                        print $user->findOne('login', $request->getParameter('login'));
                        $meldung = "Dieser Benutzername ist bereits vergeben!";
                    } else {
                        /* Wenn dieser noch nicht vergeben ist, dann werden die Daten im Datenmodell gespeichert */
                        $user->setLogin($request->getParameter('login'));
                        /* Das Passwort wird kryptografisch gespeichert */
                        $user->setPassword(password_hash($request->getParameter('password'), PASSWORD_BCRYPT));
                        /* Der neue Nutzer wird in die Datenbank geschrieben */
                        $user->insert();
                        // Die Session Variablen werden gesetzt
                        $_SESSION['login'] = 'ok';
                        $_SESSION['name'] = $request->getParameter('login');
                        /* Und alle Request Parameter gelöscht */
                        $request->deleteParameter('login');
                        $request->deleteParameter('password');
                        $request->deleteParameter('passwordrepeat');
                        /* und der Nutzer wird auf die Todo Liste weitergeleitet */
                        header('Location: '.$reg->getConfiguration()->getDomain().'/aufgabenliste/');
                        exit();

                    }
                }
            } else {
                /* Wenn nicht alle Felder des Formulars ausgefüllt wurden, dann wird folgende Meldung ausgegeben: */
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
    /**
     * Methode zur Überprüfung des Passworte
     * @param $password
     * @return bool
     */
    private function checkPassword($password) {
        if (preg_match("/^.*(?=.*[.,#+!$])(?=.*\d)(?=.*\w)(?=.{6,}).*$/", $password)) {
            return true;
        } else {
            return false;
        }

    }
    /**
     * Methode zur Überprüfung des Benutzernamens
     * @param $user
     * @return bool
     */
    private function checkUser($user) {
        if (preg_match("/[\d\w_]/", $user)) {
            return true;
        } else {
            return false;
        }

    }
}