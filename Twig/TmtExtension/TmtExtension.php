<?php

namespace Tmt\ServicesBundle\Twig\TmtExtension;

use Symfony\Component\Routing\RouterInterface;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class TmtExtension extends Twig_Extension {
    /**
     * @var RouterInterface
     */
    protected $router;
    
    /**
     * Constructor
     * 
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    /**
     * Declare the asset_url function
     */
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('assetUrl', [$this, 'assetUrl']),
        );
    }

    public function getFilters() {
        return array(
            'slice_content' => new Twig_SimpleFilter('slice_content', array($this, 'slice_content')),
        );
    }

    /**
     * Implement asset_url function
     * We get the router context. This will default to settings in
     * parameters.yml if there is no active request
     */
    public function assetUrl($path) {
        $context = $this->router->getContext();
        $host = $context->getScheme() . '://' . $context->getHost() . '/';

        return $host . $path;
    }

    public function slice_content($value, $slice, $extra = "...") {

        if (strlen(utf8_decode($value)) > $slice) {
            return mb_substr($value, 0, $slice) . ' ' . $extra;
        }

        return $value;
    }

    public function getName() {

        return 'tmt_services_extension';
    }

}
