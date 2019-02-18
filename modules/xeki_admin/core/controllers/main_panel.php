<?php
require_once dirname(__FILE__) . "/../common/main_for_controllers.php";
## controllers inicializa

// check is is logged
// $user_zone->pageLoginCheck();


// if is loged show main_panel

//d($AG_MODULES);

$xeki_auth = \xeki\module_manager::import_module('xeki_auth');
$xeki_auth->check_login();


// create list menu


$data_to_print= array();
\xeki\html_manager::render('main_panel.html', $data_to_print);