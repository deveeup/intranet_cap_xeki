<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */
namespace ag_img_resize;

use ag_img_resize\resize;

require_once dirname(__FILE__) . "/core/ag_img_resize.php";

class main
{
    public static $object = null;
    public $folder = '';

    function init($config)
    {
        $this->folder = $config['folder'];
        $this->method = $config['method'];
        $this->kraken_api_key = $config['kraken_api_key'];
        $this->kraken_api_secret = $config['kraken_api_secret'];
        return true;
    }

    function getObject()
    {
        if (self::$object == null) {
            $config = array(
                'folder' => $this->folder,
                'method' => $this->method,
                'kraken_api_key' => $this->kraken_api_key,
                'kraken_api_secret' => $this->kraken_api_secret,
            );
            self::$object = new resize($config);
        }
//        d(self::$sql);
//        $info = self::$sql->query("SELECT * FROM blog");
//        d($info);
//        die();
        return self::$object;
    }

    function check()
    {
        return true;
    }
}