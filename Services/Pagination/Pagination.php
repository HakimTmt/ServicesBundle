<?php

namespace Tmt\ServicesBundle\Services\Pagination;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Pagination service
 *
 * @author TMT
 */
class Pagination {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function paginationLink($pg, $totalPages) {
        $pagination = array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $routeName = $request->get('_route');
        $route_params = $request->get('_route_params');
        $requestParams = $request->query->all();
        if ($pg > 1) {
            $pagination['prev_page'] = $pg - 1;
            $pagination['prev_link'] = $this->container->get('router')->generate($routeName, array_merge(
                            $route_params, $requestParams, array('pg' => $pagination['prev_page'])
                    ), UrlGeneratorInterface::ABSOLUTE_URL);
        }
        if ($pg < $totalPages) {
            $pagination['next_page'] = $pg + 1;
            $pagination['next_link'] = $this->container->get('router')->generate($routeName, array_merge(
                            $route_params, $requestParams, array('pg' => $pagination['next_page'])
                    ), UrlGeneratorInterface::ABSOLUTE_URL);
        }
        return $pagination;
    }

    public function totalPages($nbr, $displayPerPage) {
            
        if($displayPerPage){
            ((int) $nbr != 0) ? $nbrArticles = $nbr : $nbrArticles = 1;
            (((int) $nbrArticles % $displayPerPage) > 0) ? $pgs = intval($nbrArticles / $displayPerPage) + 1 : $pgs = intval($nbrArticles / $displayPerPage);
            return $pgs;
            }
        
    }

}
