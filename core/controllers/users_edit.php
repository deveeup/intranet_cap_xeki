<?php

#import modules
$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');

#validate logged
if(!$auth->is_logged()){
	\xeki\core::redirect('');
} else {
	
	
	#user admin ? 
	$user = $auth->get_user(); 
	$user_admin = $user->get("is_superuser");
	if($user_admin == 'yes'){
		#info seo
		$title = "Intranet";
		$description = "Lorem...";
		$keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
		\xeki\html_manager::set_seo($title,$description,false);

		#var urls
		$username = $vars[username];
		#search users
		$queryOne = "SELECT * FROM auth_user WHERE auth_user.username = '$username' ";
		$userInfo = $sql->query($queryOne);

		#search groups
		$queryTwo = "SELECT * FROM auth_group";
		$groups = $sql->query($queryTwo);
		
		#search user groups
		$id_user = $userInfo[0]['id'];
		$user = $auth->get_user(); 

		#search cities 
		$queryThree = "SELECT * FROM cities";
		$cities = $sql->query($queryThree);

		#search company 
		$queryFour = "SELECT * FROM company";
		$business = $sql->query($queryFour);


		$search_groups = $user->get_groups_by_id($id_user);
		$groups_decode = json_encode($search_groups);
		$groups_users = json_decode($groups_decode, true);

		#sending data to view
		$items_to_print = array();
		$items_to_print['user'] = $userInfo[0];
		$items_to_print['groups'] = $groups;
		$items_to_print['groups_users'] = $groups_users;
		$items_to_print['cities'] = $cities;
		$items_to_print['business'] = $business;
		
		\xeki\html_manager::render('dashboard/user_edit.html',$items_to_print);
	}else{
		\xeki\core::redirect('');
	}
	
}
