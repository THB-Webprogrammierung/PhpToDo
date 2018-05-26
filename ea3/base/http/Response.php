<?php
namespace base\http;

interface Response {
    public function setStatus($status);
    public function addHeader($name, $value);
    public function write($data);
    public function flush();
}