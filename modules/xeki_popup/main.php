<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */
namespace xeki_popup;
require_once dirname(__FILE__) . "/core/popup.php";

class main
{
    public static $sql = null;
    public $config = array();
    public $user = '';
    public $pass = '';
    public $db = '';

    function init($config)
    {
        $this->config = $config;
        return true;
    }

    function getObject()
    {
        if (self::$sql == null) {
            $connection_information = array(
                'host' => $this->host,
                'user' => $this->user,
                'pass' => $this->pass,
                'db' => $this->db
            );
            self::$sql = new popup($this->config);
        }
//        d(self::$sql);
//        $info = self::$sql->query("SELECT * FROM blog");
//        d($info);
//        die();
        return self::$sql;
    }

    

    function check()
    {
        return true;
    }
}