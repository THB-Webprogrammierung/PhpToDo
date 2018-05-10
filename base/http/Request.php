<?php
namespace base\http;

interface Request {
    public function getParameterNames();
    public function issetParamenterName($name);
    public function getParameter($name);
    public function getHeader($name);
    public function getAuthData();
}