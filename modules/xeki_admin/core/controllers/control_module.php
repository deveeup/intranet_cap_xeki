<?php
require_once dirname(__FILE__) . "/../common/main_for_controllers.php";
require_once dirname(__FILE__) . "/../common/data_table/ssp.class.php";

// Always check auth

$xeki_auth = \xeki\module_manager::import_module('xeki_auth');
$xeki_auth->check_login();

$ag_sql = \xeki\module_manager::import_module('xeki_db_sql');
$sql = $ag_sql;
$module_admin = \xeki\module_manager::import_module('xeki_admin');

// Load big variables

$sql_details = array(
    'user' => $ag_sql->user,
    'pass' => $ag_sql->pass,
    'db' => $ag_sql->db,
    'host' => $ag_sql->host
);

$params = \xeki\core::$URL_PARAMS;
$url_base_admin = $xeki_admin->get_value_param("base_url");

if ($params[0] == $url_base_admin) {
    unset($params[0]);
    $i = 0;
    $new_params = array();
    foreach ($params as $item) {
        $new_params[$i] = $item;
        $i++;
    }
    $params = $new_params;

}
$module_code = $params[0];
$module_action_code = $params[1];
$module_action_code_method = $_GET['method'];

//d($module_code);
//d($module_action_code);
//d($module_action_code_method);

// global variables
$load_html = true;
$render_method = "";
$module = array();
$array_json = array();


$permission="{$module_code}_{$module_action_code}_{$module_action_code_method}";
$permission_valid = $xeki_auth->check_permission($permission);

// pass methods
// TODO valid url and same ws for tablets

//d($permission_valid);
//d(strpos($permission,"ws"));
if(strpos($permission,"ws")===false && false) {
    if (!$permission_valid) {
//        d($permission);
//        d("not have permission");
        // if is a request with method
        if ($module_action_code_method != "") {
            // print u dont have permission

            // and die
        } else {
            array_push($module['elements'], $element_table_user_admin);

            die();
        }

        // print html with

        //
    }
}





if(isset($_POST['xeki_admin_action'])){
    $render_method="json";
    $file_to_run = $module_admin->get_controller_for_ws();
    require($file_to_run);
    error_reporting(0);
    \xeki\html_manager::$done_render = true;
    $load_html= false;
}

if($load_html){
    $render_method = "html";
    $file_to_run = $module_admin->get_controller_for_html();
    require($file_to_run);
    \xeki\html_manager::$done_render = true;
}













//if (isset($_POST['xeki_admin_action'])) {
//    require_once dirname(__FILE__) . "/controle_module_actions_handle.php";
//    die();
//}

// for sql
//$sql_details = array(
//    'user' => $ag_sql->user,
//    'pass' => $ag_sql->pass,
//    'db' => $ag_sql->db,
//    'host' => $ag_sql->host
//);


if ($render_method == "html") {
    $data_to_print = array();
    $data_to_print['module'] = $module;
    \xeki\html_manager::render('control_module.html', $data_to_print);
}

if ($render_method == "json") {
//    echo $array_json;
//    $array_json = utf8ize($array_json);
//    $array_json = mb_convert_encoding($array_json, "UTF-8");
    $array_json = decode_array($array_json);
    $array_json = json_encode($array_json);
    echo $array_json;
}
