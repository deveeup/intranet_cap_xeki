<?php
// d("hiii!!");
// d($AG_HTTP_REQUEST);
$module_name="xeki_admin";
$module = \xeki\module_manager::import_module("$module_name");
$enable_controllers=$module->get_value_param("use_module_controllers");

if($enable_controllers){

    ## load urls for config file
    $base_url=$module->get_value_param("base_url");

    \xeki\routes::any("$base_url", 'main_panel', "$module_name");
    \xeki\routes::any("$base_url/", 'main_panel', "$module_name");

    \xeki\routes::any("$base_url/login", 'admin_auth_login', "$module_name");
    \xeki\routes::any("$base_url/logout", 'admin_auth_logout', "$module_name");

    // for inner page
    \xeki\routes::any("$base_url/{module_code}/", 'control_module', "$module_name");
    \xeki\routes::any("$base_url/{module_code}/{module_code_action}", 'control_module', "$module_name");
    \xeki\routes::any("$base_url/{module_code}/{module_code_action}/", 'control_module', "$module_name");

}

