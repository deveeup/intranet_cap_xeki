<?php
/**
 * Created by PhpStorm.
 * User: liusp
 * Date: 6/30/2016
 * Time: 12:10 PM
 */

namespace ag_sitemap;


class ag_sitemap
{
    private $config_array;

    /**
     * ag_sitemap constructor.
     */

    public function __construct($config)
    {
        // load seccion xeki_popups
        $this->config_array=$config;

        $this->type=$config['type'];
    }
    public function getConfig(){
        return $this->config_array;
    }
}