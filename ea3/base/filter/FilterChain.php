<?php
namespace base\filter;
/**
 * FilterChain
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
use base\http\Request;
use base\http\Response;

class FilterChain {

    private $filters = array();

    public function addFilter(Filter $filter) {
        $this->filters[] = $filter;
    }

    public function processFilters(Request $request, Response $response) {
        foreach($this->filters as $filter) {
            $filter->execute($request, $response);
        }
    }

}