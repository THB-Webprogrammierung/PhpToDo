<?php
/**
 * Front Controller Pattern
 * Ein Front-Controller ist ein Objekt, das alle Anfragen an eine Webapplikation entgegennimmt und die Arbeiten durchführt,
 * die bei allen Anfragen identisch sind. Zur Erzeugung der Antwort auf die Anfrage leitet es die Anfragen an die Objekte weiter,
 * die die unterschiedlichen Anfragen verarbeiten können.
 */

session_set_cookie_params ( 0, "/", "", FALSE, FALSE );
session_start();
require 'autoload.php';
require 'config.inc.php';

use base\http\HttpRequest;
use base\http\HttpResponse;
use base\command\FileSystemCommandResolver;
use base\FrontController;
use base\config\Registry;
use base\filter\HttpAuthFilter;

$resolver = new FileSystemCommandResolver('controller', 'Index');
$controller = new FrontController($resolver);

$registry = Registry::getInstance();
$registry->setConfiguration($configuration);

$authFilter = new HttpAuthFilter(array('admin' => 'admin'));
$controller->addPreFilter($authFilter);

$request = new HttpRequest();
$response = new HttpResponse();

$controller->handleRequest($request, $response);






