<?php
namespace base;
/**
 * Front Controller
 *
 * @author Jens Bekersch <bekersch@th-brandenburg.de>
 * @author Tim Schulz <timschulz1985@web.de>
 * @author Ines Güssow <ines.guessow@th-brandenburg.de
 * @version 1.0 05/2018
 */
use base\http\Request;
use base\http\Response;
use base\command\CommandResolver;
use base\filter\Filter;
use base\filter\FilterChain;

class FrontController {

    private $resolver;
    private $preFilters;
    private $postFilters;

    public function __construct(CommandResolver $resolver) {
        $this->resolver = $resolver;
        $this->preFilters = new FilterChain();
        $this->postFilters = new FilterChain();
    }

    public function handleRequest(Request $request, Response $response) {
        $this->preFilters->processFilters($request, $response);
        $command = $this->resolver->getCommand($request);
        $command->execute($request, $response);
        $this->postFilters->processFilters($request, $response);
        $response->flush();
    }

    public function addPreFilter(Filter $filter) {
        $this->preFilters->addFilter($filter);
    }

    public function addPostFilter(Filter $filter) {
        $this->postFilters->addFilter($filter);
    }

}