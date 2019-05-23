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

	#user admin ? 
	$user = $auth->get_user(); 
	$user_admin = $user->get("is_superuser");
	if($user_admin == 'yes'){
		
		#search users
		$queryOne = "SELECT * FROM auth_user order by id";
		$users = $sql->query($queryOne);
		$info_users = array();
		foreach ($users as $user){
			$user[info_city] = array();
			#search cities
			$queryTwo = "SELECT * FROM cities WHERE cities.id = '$user[city]' order by id";
			$cities = $sql->query($queryTwo);
			foreach ($cities as $city){
				if($city['id'] == $user['city']){
					array_push($user[info_city], $city);
				}
			}
			array_push($info_users, $user);
		}
		#sending data to view
		$items_to_print = array();
		$items_to_print['users'] = $info_users;
		\xeki\html_manager::render('dashboard/users.html',$items_to_print);
	}else{
		\xeki\core::redirect('');
	}
	
}
