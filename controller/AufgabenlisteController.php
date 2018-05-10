<?php
namespace controller;

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

    public function execute(Request $request, Response $response) {
        /* Konfigurationsvariablen */
        $reg = Registry::getInstance();
        $user = new User();
        $user->findOne("login", $_SESSION['name']);
        $viewHelper = new UppercaseViewHelper();

        if($request->getRequestMethod() == 'POST') {
            $todo = new Todo();
            $todo->setText($_POST['text']);
            $todo->setOwner($user->getId());
            $todo->insert();
        }

        $todos = new Todos();
        $todos->getTodos($todos->find("owner", $user->getId()));

        if($request->getRequestMethod() == 'GET' && isset($_GET['done']) && $_GET['done'] != '') {
            $todos->getTodo($_GET['done'])->getDone() ? $todos->getTodo($_GET['done'])->setDone('0') : $todos->getTodo($_GET['done'])->setDone('1');
            $todos->getTodo($_GET['done'])->save();
        }
        if($request->getRequestMethod() == 'GET' && isset($_GET['delete']) && $_GET['delete'] != '') {
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