<?php
namespace base\command;

use base\http\Request;
use base\http\Response;

interface Command {
    public function execute(Request $request, Response $response);
}