<?php
/**
 * Todo Liste
 *
 * Todo Liste für mehrere Benutzer, in der jeder Benutzer genau eine Todo Liste anlegen und verwalten kann.
 * Für die programmatische Umsetzung wird ein MVC Pattern genutzt. Die benötigten Klassen für den Start des
 * Programmablaufs werden in dieser Datei initialisiert.
 * Model: Die Datenmodelle befinden sich im Unterordner 'model'
 * View: Die HTML Dateien befinden sich im Unterordner 'views', Stylesheets befinden sich im Unterordner 'styles',
 *       wiederkehrend benötigter Code für die Manipulation von HTML Elementen und deren Inhalt befinden sich im Unterordner 'viewHelper'
 * Controller: Die Controller befinden sich im Unterordner 'controller'
 * Das Framework befindet sich im Unterordner 'base'
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */

/* Start Session für das Login System */
session_set_cookie_params ( 0, "/", "", FALSE, FALSE );
session_start();
/* Einbinden benötigter Dateien - Autoload und Konfiguration */
require 'autoload.php';
require 'config.php';
/* Einbinden benötigter Klassen */
use base\http\HttpRequest;
use base\http\HttpResponse;
use base\command\FileSystemCommandResolver;
use base\FrontController;
use base\config\Registry;
use base\filter\HttpAuthFilter;
/**
 * File System Resolver / Front Controller
 *
 * Der Front Controller ist dafür zuständig, dass die aufgerufene Seite angezeigt wird.
 * Standardmäßig wird der Index Controller aufgerufen, wenn kein anderweitiger gültiger command vorhanden ist.
 */
$resolver = new FileSystemCommandResolver('controller', 'Index');
$controller = new FrontController($resolver);
/**
 * Registry
 *
 * Die Registry verwaltet Konfigurationskonstanten, die während des Programmablaufs mehrfach gebraucht werden.
 * Hier wird diese instanziiert, um die Konfigurationsparameter an die entsprechende Klasse zu übergeben.
 */
$registry = Registry::getInstance();
$registry->setConfiguration($configuration);
/**
 * HttpAuthFilter
 *
 * Ein Prefilter, der sicherstellt, dass nur die als im Filter öffentlich hinterlegten Seiten ohne Zugangsberechtigung verfügbar sind.
 */
$authFilter = new HttpAuthFilter(array('admin' => 'admin'));
$controller->addPreFilter($authFilter);
/**
 * HttpRequest & HttpResponse
 *
 * Initialisierung der Request und Response Objekte
 */
$request = new HttpRequest();
$response = new HttpResponse();
/**
 * Aufruf der Methode 'handleRequest' des FrontControllers
 */
$controller->handleRequest($request, $response);





