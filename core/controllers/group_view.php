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
		#id group 
		$id_group = $vars["group_id"];

		#info group
		$queryOne = "SELECT * FROM auth_group WHERE id = '$id_group'";
		$group_info = $sql->query($queryOne);

		#relationship user & group
		$queryTwo = "SELECT * FROM auth_user_group WHERE group_ref = '$id_group'";
		$group_user = $sql->query($queryTwo);

		#send data users
		$user_list = array();
		foreach ($group_user as $group){

			$queryThree = "SELECT * FROM auth_user WHERE auth_user.id = '$group[user_ref]' ";
			$array_user = $sql->query($queryThree);

			$queryFive = "SELECT * FROM auth_user_permission WHERE auth_user_permission.user_ref = '$group[user_ref]' ";
			$array_user_permission = $sql->query($queryFive);

			foreach ($array_user_permission as $id_group ){
				// array_push($array_user[0], $array_user_permission[0]);
				$groupRef = $id_group['group_ref'];
				$querySix = "SELECT * FROM auth_group WHERE id = '$groupRef' ";
				$infoGroupUser = $sql->query($querySix);
				array_push($array_user[0], $infoGroupUser);
			}

			array_push($user_list, $array_user[0]);
		}
		d($user_list);

		#created group user
		$id_user_create_group = $group_info[0]['created_by'];
		$queryFour = "SELECT * FROM auth_user WHERE id = '$id_user_create_group'";
		$info_by_create = $sql->query($queryFour);
		$create_by = $info_by_create[0]['first_name'] .' '. $info_by_create[0]['last_name'];

		#

		#sending data to view
		$items_to_print = array();
		$items_to_print['group'] = $group_info[0];
		$items_to_print['users'] = $user_list;
		$items_to_print['create_by'] = $create_by;
		\xeki\html_manager::render('dashboard/group_view.html',$items_to_print);
	}else{
		\xeki\core::redirect('');
	}
	
}
