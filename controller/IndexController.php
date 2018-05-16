<?php
namespace controller;
/**
 * Index Controller
 *
 * Controller für die Startseite
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

class IndexController implements Command {
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
        /* Laden der Konfigurationsvariablen */
        $reg = Registry::getInstance();
        /* Prüfvariable: hier wird gespeichert, ob eine erfolgreiche Authentigizierung im Anmeldeprozess stattgefunden hat */
        $authenticationSuccess = false;
        /* Initialisierung der String Variablen für eine etwaige Meldung, wie zum Beispiel 'Login war nicht erfolgreich' */
        $meldung = "";
        /* Benutzerabmeldung: Die Session wird gelöscht */
        if($request->getRequestMethod() == 'GET' && isset($_GET['logout']) && $_GET['logout'] == "true") {
            session_unset();
        }
        /* Wenn der Nutzer bereits erfolgreich authentifiziert ist, wird dieser auf die Todo Liste weitergeleitet */
        if(isset($_SESSION['login']) && $_SESSION['login'] == "ok") {
            header('Location: '.$reg->getConfiguration()->getDomain().'/aufgabenliste/');
            exit();
        }
        /* Wenn der Benutzer das Anmeldeformular abgesendet hat */
        if($request->getRequestMethod() == 'POST') {
            /* und die Variablen 'login' und 'passwort' gesetzt sind */
            if(isset($_POST['login']) && isset($_POST['password'])) {
                /* wird ein User Datenmodell Objekt initialisiert */
                $user = new User();
                /* eine Datenbankabfrage mit dem hinterlegten Nutzernamen durchgeführt */
                $user->findOne("login", $_POST['login']);
                /* und wenn diese erfolgreich ist */
                if($user->getId() > -1) {
                    /* dann wird das Passwort verifiziert */
                    if(password_verify($_POST['password'], $user->getPassword())) {
                        /* und die lokale Variable zum speichern eines erfolgreichen Logins mit dem Wert 'true' versehen */
                        $authenticationSuccess = true;
                    }
                }
            }
            /* Wenn die Authentifizierung erfolgreich war */
            if($authenticationSuccess === true) {
                /* werden die Session Variablen gesetzt */
                $_SESSION['login'] = 'ok';
                $_SESSION['name'] = $user->getLogin();
                /* und der Nutzer wird auf die Todo Liste weitergeleitet */
                header('Location: '.$reg->getConfiguration()->getDomain().'/aufgabenliste/');
                exit();
            } else {
                /* Wenn die Authentigizierung nicht erfolgreich war, dann wird folgende ausgegeben: */
                $meldung = "Sie haben ungültige Zugangsdaten eingegeben. Bitte korrigieren Sie diese!";
            }
        }
        /* Template initialisieren */
        $view = new HtmlTemplateView('login');
        /* Dem Template werden die nötigen Daten zuweisen */
        $view->assign('domain', $reg->getConfiguration()->getDomain());
        $view->assign('seite', "Todolist - Login");
        $view->assign('meldung', $meldung);
        /* Die View rendern */
        $view->render($request, $response);
    }
}