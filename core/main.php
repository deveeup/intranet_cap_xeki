<?php

#import modules
$auth = \xeki\module_manager::import_module('auth');
$csrf = \xeki\module_manager::import_module('csrf');

#global data
\xeki\html_manager::add_extra_data("cap","https://capillasdelafe.com/funeraria");
\xeki\html_manager::add_extra_data("coor","https://coorserpark.com/");
\xeki\html_manager::add_extra_data("alt","Intranet Funeraria Capillas de La Fe -");
\xeki\html_manager::add_extra_data("csrf", $csrf->get_token_html()); #token csrf

#validate logged
$status = $auth->is_logged();

if($status){
    $user = $auth->get_user();
    $user_info = $user->get_info();

    \xeki\html_manager::add_extra_data("auth_user_info", $user_info);    

    \xeki\html_manager::add_extra_data("last_name", $user->get("last_name"));
    \xeki\html_manager::add_extra_data("email", $user->get("email"));
    \xeki\html_manager::add_extra_data("first_name", $user->get("first_name"));

}

