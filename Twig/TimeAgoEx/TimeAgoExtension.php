<?php

namespace Tmt\ServicesBundle\Twig\TimeAgoEx;


class TimeAgoExtension extends \Twig_Extension

{
    
     /**
         * @var ContainerInterface
         */
        protected $container;
        /**
         * Constructor
         * 
         * @param ContainerInterface $container
         */
        public function __construct($container)
        {
           
            $this->container = $container;

        }
        

    public function getFilters() {
        return array(
            'TimeAgo' => new \Twig_SimpleFilter('TimeAgo', array($this, 'TimeAgo')),
           
        );
    }
    


    public function TimeAgo($date){
        
            # TimeAgo Exemple : 
            # "2017-04-28 13:45" date|TimeAgo
            
            if(empty($date)) {
                return "No date provided";
            }

            $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
            $lengths         = array("60","60","24","7","4.35","12","10");

            $now             = time();
            $unix_date         = strtotime($date);

               // check validity of date
            if(empty($unix_date)) {    
                return "Bad date";
            }

            // is it future date or past date
            if($now > $unix_date) {    
                $difference     = $now - $unix_date;
                $tense         = "ago";

            } else {
                $difference     = $unix_date - $now;
                $tense         = "from now";
            }

            for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
                $difference /= $lengths[$j];
            }

            $difference = round($difference);

            if($difference != 1) {
                $periods[$j].= "s";
            }

            return "$difference $periods[$j] {$tense}";
        
      
        
       
    }
    

   

    public function getName() {

        return 'octelio_time_ago_extension';
    }

}
