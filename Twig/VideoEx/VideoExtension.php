<?php

namespace Tmt\ServicesBundle\Twig\VideoEx;

use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Extension twig to geberate iframe Video
 *
 * @author adouiri@techmyteam.com
 * @author aaboulhaj@techmyteam.com
 */
class VideoExtension extends Twig_Extension {

    

    public function getFilters() {
        return array(
            'generateVideo' => new Twig_SimpleFilter('generateVideo', array($this, 'generateVideo')),
        );
    }
    /**
     * Constructor
     * 
     * @param string $value
     * @param int $aWidth
     * @param int $aHeight
     * 
     *  # Exemple links: 
     *  # http://dai.ly/x2epiza 
     *  # http://www.dailymotion.com/video/x2epiza_ubisoft-jeux-video-les-lapins-cretins-et-la-fin-du-monde-decembre-2012-petage-de-plomb_creation
     *  # http://youtu.be/sOML64y5dfQ
     *  # https://vimeo.com/184065357
     */
    public function generateVideo($value, $aWidth = 640, $aHeight = 360) {
        $h = '';
        $aUrl = $value;

        if (strpos($aUrl, 'dailymotion') !== false) {
            // dailymotion
            $d = strpos($aUrl, 'video/');
            if ($d !== false) {
                $f = strpos($aUrl, '_', $d);
                $vid = substr($aUrl, $d + 6, $f - $d - 6);
                $h = '<iframe frameborder="0" width="' . $aWidth . '" height="' . $aHeight . '" src="//www.dailymotion.com/embed/video/' . $vid . '" allowfullscreen></iframe>';
            }
        } elseif (strpos($aUrl, 'dai.ly') !== false) {
            // dailymotion
            $d = strpos($aUrl, 'dai.ly/');
            if ($d !== false) {
                $vid = substr($aUrl, $d + 7);
                $h = '<iframe frameborder="0" width="' . $aWidth . '" height="' . $aHeight . '" src="//www.dailymotion.com/embed/video/' . $vid . '" allowfullscreen></iframe>';
            }
        } elseif (strpos($aUrl, 'youtube') !== false) {
            // youtube
            $d = strpos($aUrl, 'v=');
            if ($d !== false) {
                $vid = substr($aUrl, $d + 2);
                $vid = preg_replace("/(.*?)&(.*)/", "$1", $vid);
                $h = '<iframe width="' . $aWidth . '" height="' . $aHeight . '" src="https://www.youtube.com/embed/' . $vid . '" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>';
            }
        } elseif (strpos($aUrl, 'youtu.be') !== false) {
            // youtu.be
            $d = strpos($aUrl, 'youtu.be/');
            if ($d !== false) {
                $vid = substr($aUrl, $d + 9);
                $vid = preg_replace("/(.*?)&(.*)/", "$1", $vid);
                $h = '<iframe width="' . $aWidth . '" height="' . $aHeight . '" src="https://www.youtube.com/embed/' . $vid . '" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>';
            }
        } elseif (strpos($aUrl, 'vimeo') !== false) {
            // vimeo
            $d = strpos($aUrl, 'vimeo.com/');
            if ($d !== false) {
                $vid = substr($aUrl, $d + 10);
                $h = '<iframe src="https://player.vimeo.com/video/' . $vid . '" width="' . $aWidth . '" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            }
        }
        return $h;
    }

    public function getName() {

        return 'tmt_service_video_extension';
    }

}
