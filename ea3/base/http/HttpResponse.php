<?php
namespace base\http;
/**
 * HttpResponse
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
class HttpResponse implements Response {

    private $status = '200 ok';
    private $headers = array();
    private $body = null;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function write($data) {
        if(!is_array($data))
            $this->body .= $data;
    }

    public function flush()
    {
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        header("HTTP/1.0 {$this->status}");
        print $this->body;
        $this->headers = array();
        $this->data = null;
    }
}