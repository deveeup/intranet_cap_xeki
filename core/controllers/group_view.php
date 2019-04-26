<?php

#import modules
$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');

#validate logged
if(!$auth->is_logged()){
	\xeki\core::redirect('');
} else {
	#info seo
	d($vars);
	$title = "Intranet";
	$description = "Lorem...";
	$keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
	\xeki\html_manager::set_seo($title,$description,false);

	#user admin ? 
	$user = $auth->get_user(); 
	$user_admin = $user->get("is_superuser");
	if($user_admin == 'yes'){
		
		#search groups
		$queryOne = "SELECT * FROM auth_group order by id";
		$groups = $sql->query($queryOne);
		
		#sending data to view
		$items_to_print = array();
		$items_to_print['groups'] = $groups;
		\xeki\html_manager::render('dashboard/groups.html',$items_to_print);
	}else{
		\xeki\core::redirect('');
	}
	
}
