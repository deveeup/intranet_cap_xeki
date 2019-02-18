<?php
require_once dirname(__FILE__) . "/../common/main_for_controllers.php";

// $user_zone->pageLoginCheck();

//echo "hi!!";
//$sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');

//d($sql->query("SELECT now()"));
//
//d($AG_MODULES);
//d($AG_HTML);
//d($AG_MODULES);

$xeki_auth = \xeki\module_manager::import_module('xeki_auth');

$is_auth = $xeki_auth->check_auth();
if($is_auth){
    $user_info = $xeki_auth->check_logged();
    $xeki_auth->go_to_logged();
}

//$user_zone->set_logged_page("xeki_admin");
//$xeki_auth->check_login();


\xeki\html_manager::render('login.html', $data_to_print);