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

$xeki_auth = \xeki\module_manager::import_module('xeki_auth');


$xeki_auth->pageLoginCheck();

$folder_auth=$xeki_auth->get_folder();
$array_data['xeki_auth_temp_check_user']=isset($_SESSION['xeki_auth::temp_check_user'])?$_SESSION['xeki_auth::temp_check_user']:"";
\xeki\html_manager::render("{$folder_auth}/auth_login.html", $array_data);