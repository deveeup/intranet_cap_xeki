<?php

#import modules
$auth = \xeki\module_manager::import_module('auth');
$csrf = \xeki\module_manager::import_module('csrf');
$sql=\xeki\module_manager::import_module("db-sql");

#global data
\xeki\html_manager::add_extra_data("cap","https://capillasdelafe.com/funeraria");
\xeki\html_manager::add_extra_data("coor","https://coorserpark.com/");
\xeki\html_manager::add_extra_data("alt","Intranet Funeraria Capillas de La Fe -");
\xeki\html_manager::add_extra_data("csrf", $csrf->get_token_html()); #token csrf

#validate logged
$status = $auth->is_logged();



if($status){
	$query = "SELECT * FROM transversal_process order by order_by asc";
	$transversal_process_global = $sql->query($query);

	$items_to_global_print = array();
	$items_to_global_print['transversal_process_global'] = $transversal_process_global;
	
	$user = $auth->get_user();
	$user_info = $user->get_info();

	\xeki\html_manager::add_extra_data("auth_user_info", $user_info);    

	\xeki\html_manager::add_extra_data("last_name", $user->get("last_name"));
	\xeki\html_manager::add_extra_data("email", $user->get("email"));
	\xeki\html_manager::add_extra_data("first_name", $user->get("first_name"));
	\xeki\html_manager::add_extra_data("transversal_process_global", $items_to_global_print['transversal_process_global']);
	setcookie("update_password_successful",false,time()+1);

	$user_admin = $user->get("is_superuser");
	if($user_admin == 'yes'){
		\xeki\html_manager::add_extra_data("admin", "yes");
	}

}else{
	$varUpdate = $_COOKIE["update_password_successful"];

	if($varUpdate){
		\xeki\html_manager::add_extra_data("update_password_successful","La contraseña se ha actualizado de manera éxitosa");
	}

}

