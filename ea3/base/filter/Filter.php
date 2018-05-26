<?php
namespace base\filter;

use base\http\Request;
use base\http\Response;

interface Filter {
    public function execute(Request $request, Response $response);
}