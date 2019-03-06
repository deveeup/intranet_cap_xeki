<?php
namespace ag_sitemap;

require_once dirname(__FILE__) . "/core/common/ag_sitemap.php";
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */





class main
{
    public static $object = null;

    private $config = array();

    function __construct()
    {
    }

    function init($config)
    {
        $this->config = $config;
        return true;
    }

    function getObject()
    {
        if (self::$object == null) {
            self::$object = new ag_sitemap($this->config);
        }
//        d(self::$sql);
//        $info = self::$sql->query("SELECT * FROM blog");
//        d($info);
//        die();
        return self::$object;
    }
    function set_up_pages(){}
    function check()
    {
        return true;
    }




}