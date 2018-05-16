<?php
namespace controller;
/**
 * Aufgabenliste Controller
 *
 * Controller für die Aufgabenliste
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
use model\Todos;
use viewHelper\UppercaseViewHelper;
use model\User;
use model\Todo;

class AufgabenlisteController implements Command {
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
        /* Konfigurationsvariablen */
        $reg = Registry::getInstance();
        /* Das Datenmodell für den Benutzer wird instanziiert und das Datenmodell mit den Nutzerdaten aus der Datenbank befüllt */
        $user = new User();
        $user->findOne("login", $_SESSION['name']);
        /* Der UppercaseViewHelper dient dazu, Wörter in Großbuchstaben darzustellen */
        $viewHelper = new UppercaseViewHelper();
        /* Wenn der Nutzer das Formular für einen neuen Todo Eintrag abgesendet hat, dann */
        if($request->getRequestMethod() == 'POST') {
            /* wir ein neues Todo Objekt instanziiert */
            $todo = new Todo();
            /* das Objekt wird mit den Daten befüllt */
            $todo->setText($_POST['text']);
            $todo->setOwner($user->getId());
            /* und die Daten werden in die Datenbank geschrieben */
            $todo->insert();
        }
        /*
         * Die Todos Liste wird instanziiert und alle Todos des angemeldeten Benutzers werden aus der Datenbank ausgelesen
         * und in der Liste gespeichert.
        */
        $todos = new Todos();
        $todos->getTodos($todos->find("owner", $user->getId()));
        /* Wenn eine Aufgabe als erledigt oder wieder als noch nicht erledigt markiert wurde, dann */
        if($request->getRequestMethod() == 'GET' && isset($_GET['done']) && $_GET['done'] != '') {
            /* wird zuerst geprüft, in welchem Status sich die Aufgabe befindet, um den Wert 'true' oder 'false' im Datenmodell zu speichern */
            $todos->getTodo($_GET['done'])->getDone() ? $todos->getTodo($_GET['done'])->setDone('0') : $todos->getTodo($_GET['done'])->setDone('1');
            /* um die Veränderung anschliessen zu persistieren */
            $todos->getTodo($_GET['done'])->save();
        }
        /* Wenn eine Aufgabe gelöscht werden soll, dann */
        if($request->getRequestMethod() == 'GET' && isset($_GET['delete']) && $_GET['delete'] != '') {
            /* wird diese direkt in der Datenbank gelöscht */
            $todos->getTodo($_GET['delete'])->delete();
        }
        /* Template initialisieren */
        $view = new HtmlTemplateView('aufgabenliste');
        /* Dem Template die nötigen Daten zuweisen */
        $view->assign('domain', $reg->getConfiguration()->getDomain());
        $view->assign('seite', "Todolist - Aufgabenübersicht");
        $view->assign('username', $viewHelper->execute($user->getLogin()));
        $view->assign('todos', $todos);
        $view->assign('anzahlTodos', $todos->count("owner", $user->getId()));
        /* Die View rendern */
        $view->render($request, $response);
    }
}