<?php
set_time_limit ( 0);
/**
 * Created by PhpStorm.
 * User: Luis Eduardo
 * Date: 2/12/2016
 * Time: 1:43 PM
 */
require_once(dirname(__FILE__) . './../libs/xeki_util_methods.php');
require_once(dirname(__FILE__) . './../libs/xeki_core/module_manager.php');
$AG_MODULES = new \xeki\module_manager();

$sql_to_create = array();

$AG_MODULES->run_setup_db();