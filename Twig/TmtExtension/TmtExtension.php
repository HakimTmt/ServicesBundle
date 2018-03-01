<?php

namespace Tmt\ServicesBundle\Twig\TmtExtension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class TmtExtension extends Twig_Extension {

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     * 
     * @param ContainerInterface $container
     */
    public function __construct($container) {

        $this->container = $container;
    }

    /**
     * Declare the asset_url function
     */
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('assetUrl',[$this, 'assetUrl']),
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
        $context = $this->container->get('router')->getContext();
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

        return 'octelio_octelio_extension';
    }

}
