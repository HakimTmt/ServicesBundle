<?php

namespace Tmt\ServicesBundle\Services\Pagination;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

/**
 * Pagination service
 *
 * @author adouiri@techmyteam.com
 * @author aaboulhaj@techmyteam.com
 */
class Pagination {

    /**
     * @var Router
     */
    protected $router;
    
    protected $requestStack;

    /**
     * Constructor
     * 
     * @param Router $router
     */
    public function __construct(Router $router , $requestStack) {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * @param int $pg
     * @param int $totalPages
     * @return array 
     */
    public function paginationLink($pg, $totalPages) {
        $pagination = array();
        $request = $this->requestStack->getCurrentRequest();
        $routeName = $request->get('_route');
        $route_params = $request->get('_route_params');
        $requestParams = $request->query->all();
        if (1 < $pg ) {
            $pagination['prev_page'] = $pg - 1;
            $pagination['prev_link'] = $this->router->generate($routeName, array_merge(
                            $route_params, $requestParams, array('pg' => $pagination['prev_page'])
                    ), UrlGeneratorInterface::ABSOLUTE_URL);
        }
        if ($pg < $totalPages) {
            $pagination['next_page'] = $pg + 1;
            $pagination['next_link'] = $this->router->generate($routeName, array_merge(
                            $route_params, $requestParams, array('pg' => $pagination['next_page'])
                    ), UrlGeneratorInterface::ABSOLUTE_URL);
        }
        return $pagination;
    }

    /**
     * @param int $nbr
     * @param int $displayPerPage
     * @return int 
     */
    public function totalPages($nbr, $displayPerPage) {

        if ($displayPerPage) {
            (0 != (int) $nbr ) ? $nbrItems = $nbr : $nbrItems = 1;
            (((int) $nbrItems % $displayPerPage) > 0) ? $pgs = intval($nbrItems / $displayPerPage) + 1 : $pgs = intval($nbrItems / $displayPerPage);
            return $pgs;
        }
    }

}
