<?php
namespace base\view;

use base\http\Request;
use base\http\Response;
use base\config\Registry;

class HtmlTemplateView implements TemplateView {

    private $template;
    private $vars = array();
    private $helpers = array();

    public function __construct($template) {
        $this->template = $template;
    }

    public function assign($name, $value) {
        $this->vars[$name] = $value;
    }

    public function render(Request $request, Response $response) {
        ob_start();
        $reg = Registry::getInstance();
        $filname = $reg->getConfiguration()->getRootDirectory() . "/views/{$this->template}.php";
        include_once $filname;
        $data = ob_get_clean();
        $response->write($data);
    }

    public function __get($property) {
        if(isset($this->vars)) {
            return $this->vars[$property];
        }
        return null;
    }

    protected function loadViewHelper($helper) {
        $reg = Registry::getInstance();
        $helperName = ucfirst($helper); if (!isset( $this->helpers[$helper])) {
            $className = 'viewHelper\\' . $helperName . 'ViewHelper';
            $fileName = $reg->getConfiguration()->getRootDirectory() . "/{$className}.php";
            if (!file_exists($fileName)) {
                return null;
            }
            include_once $fileName;
            $this->helpers[$helper] = new $className();
        }
        return $this->helpers[$helper];
    }

    public function __call($methodName, $args) {
        $helper = $this->loadViewHelper($methodName);
        if ($helper === null) {
            return "Unbekannter ViewHelper $methodName";
        }
        $val = $helper->execute($args);
        return $val;
    }

}