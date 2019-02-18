<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */
namespace xeki_admin;


require_once dirname(__FILE__) . "/core/common/xeki_admin.php";

class main
{
    public static $object = null;
    public $folder_pages = '';
    public $folder_base = '';
    private $default_pages = true;
    private $config = array();

    function __construct()
    {
    }

    function init($config)
    {
        // validate params v1 y should do this better :) 
        $required_items=array(
            'folder_pages',
            'default_pages',
            'folder_base',
        );
        
        
        foreach ($required_items as $value) {            
            if(!isset($config[$value])){
                echo "ERROR CONFIG MODULE user zone<br>";
                echo "$value<br>";
                die();
            }          

        }
        # for set custom folder pages
        $this->default_pages = $config['default_pages'];
        $this->folder_base = $config['folder_base'];
        $this->config=$config;
        return true;
    }
    

    function getObject()
    {
        if (self::$object == null) {

            $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
            self::$object = new xeki_admin($this->config, $sql);
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

    function set_up_pages()
    {
        global $AG_HTML;

        if ($this->default_pages) {
            
            $AG_HTML->set_path(dirname(__FILE__) . "/core/pages/");
        }
        else{
            
            $AG_HTML->set_path(dirname(__FILE__) . "/../../{$this->folder_base}");
        }

    }

    function set_up_db($config)
    {

        $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
        require_once dirname(__FILE__) . "../_common/sql_lib.php";
        $array_sql = array(
            "table" => $config['table'],
            "elements" => array(
                $config['field_id'] => 'number',
                $config['field_user'] => 'text',
                $config['field_password'] => 'text',
                $config['field_recover_code'] => 'text',
            ),
        );

        createSql($array_sql, $sql);
    }

}