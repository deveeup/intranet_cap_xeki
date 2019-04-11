<?php

#import modules
$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');

#validate logged
if(!$auth->is_logged()){
	\xeki\core::redirect('');
} else {
	#info seo
	$title = "Intranet";
	$description = "Lorem...";
	$keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
	\xeki\html_manager::set_seo($title,$description,false);


	#info user
	$user = $auth->get_user(); 
	$data['user'] = $user->get_info();

	#info group
	$id_user = $user->get("id");
	$query_search_notice = "SELECT group_ref FROM auth_user_group WHERE user_ref = '$id_user' ";
	$query_notice = $sql->query($query_search_notice);

	$id_notice = $query_notice[0]['group_ref'];

	#query notices
	$notices = "SELECT * FROM notices WHERE group_ref = '$id_notice' ";
	$notices_data = $sql->query($notices);

	
	#sending data to view
	$items_to_print = array();
	$items_to_print['user'] = $data['user'];
	$items_to_print['notices'] = $notices_data;

	
	\xeki\html_manager::render('index.html',$items_to_print);
}
