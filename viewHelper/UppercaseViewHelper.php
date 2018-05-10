<?php
namespace viewHelper;

use base\view\helper\ViewHelper;

class UppercaseViewHelper implements ViewHelper {

    public function execute($args) {
        return strtoupper($args);
    }

}