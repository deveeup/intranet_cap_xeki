<?php
/**
 * Created by PhpStorm.
 * User: liusp
 * Date: 4/10/2016
 * Time: 6:13 PM
 */

//echo "hi!!";
//$sql = $AG_MODULES->ag_module_import('xeki_db_sql', 'main');

//d($sql->query("SELECT now()"));
//
//d($AG_MODULES);
//d($AG_HTML);
//d($AG_MODULES);
$array_data = array();
$_SESSION['recover_code']=$AG_PARAMS[count($AG_PARAMS)-1];
$array_data['email_temp'] = isset($_SESSION['email_temp'])?$_SESSION['email_temp']:"";

$user_zone = \xeki\module_manager::import_module('xeki_auth');
$folder_auth=$user_zone->get_folder();
\xeki\html_manager::render("{$folder_auth}/auth_recover_pass_update.html", $array_data);