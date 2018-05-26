<?php
namespace base\command;

use base\http\Request;

interface CommandResolver {
    public function getCommand(Request $request);
}