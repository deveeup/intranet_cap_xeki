<?php

#import modules
$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');



#validate logged
if(!$auth->is_logged()){
	\xeki\core::redirect('');
} else {
	#drop vars
	$username = $vars['username'];

	#info page
	$title = "Intranet";
	$description = "Perfil de ";
	$keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
	\xeki\html_manager::set_seo($title,$description,false);

	#search user
	$queryOne = "SELECT * FROM auth_user WHERE auth_user.username = '$username' ";
	$array_user = $sql->query($queryOne);
	$city = $array_user[0][city];
	#search cities 
	$queryFour = "SELECT * FROM cities WHERE cities.id = '$city' ";
	$cityName = $sql->query($queryFour);

	if($array_user){
		$idUser = $array_user[0][id];
		
		$queryTwo = "SELECT * FROM auth_user_permission WHERE auth_user_permission.user_ref = '$idUser' ";
		$info_admin = $sql->query($queryTwo);
	
		$info_group_admin = array();
		
		foreach ($info_admin as $info_admin_alone){
			$first_array_group = array();
			array_push($first_array_group, $info_admin_alone);

			$queryThree = "SELECT * FROM auth_group WHERE auth_group.id = '$info_admin_alone[group_ref]' ";
			$array_group_info = $sql->query($queryThree);
			array_push($first_array_group, $array_group_info[0]);
			array_push($info_group_admin, $first_array_group);
		}

		#sending data to view
		$items_to_print = array();
		$items_to_print['user'] = $array_user[0];
		$items_to_print['info_group'] = $info_group_admin;
		$items_to_print['city'] = $cityName[0];
		\xeki\html_manager::render('view_user.html', $items_to_print);
	}else {
		#info page
		$title = "Intranet";
		$description = "Perfil de ";
		$keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
		\xeki\html_manager::set_seo($title,$description,false);

		$items_to_print = array();
		$items_to_print['info_group'] = $info_group_admin;
		\xeki\html_manager::render('404/index.html', $items_to_print);
	}
	
	}
