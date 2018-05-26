<?php
namespace base\view;

use base\http\Request;
use base\http\Response;

interface TemplateView {
    public function assign($name, $value);
    public function render(Request $request, Response $response);
}