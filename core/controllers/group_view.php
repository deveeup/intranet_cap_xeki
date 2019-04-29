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
		
		#relationship user & group (users in group)
		$queryTwo = "SELECT * FROM auth_user_group WHERE group_ref = '$id_group' ";
		$group_user = $sql->query($queryTwo);

		#send data users
		$user_list = array();
		foreach ($group_user as $group){
			#list users (complete info)
			$queryThree = "SELECT * FROM auth_user WHERE auth_user.id = '$group[user_ref]' ";
			$array_user = $sql->query($queryThree);
			$info_users = $array_user[0];
			$info_users[permission] = array();

			foreach ($array_user as $user){
				$queryFour = "SELECT * FROM auth_user_permission WHERE auth_user_permission.user_ref = '$user[id]' AND auth_user_permission.group_ref = '$id_group' ";
				$response_auth_user_permission = $sql->query($queryFour);
				
				foreach ($response_auth_user_permission as $permission){
					$user_group = array();
					if($user['id'] == $permission['user_ref']){

						$queryFive = "SELECT * FROM auth_group, auth_permission WHERE auth_permission.id = '$permission[permission_ref]' AND auth_group.id = '$permission[group_ref]' ";
						$info_group_permission = $sql->query($queryFive);
						// d($info_group_permission[0]);
						array_push($user_group, $info_group_permission[0]);

						array_push($info_users[permission], $user_group[0]);
					}
				}
			}
			array_push($user_list, $info_users);
		}
		// d($user_list);
		
		#created group user
		$id_user_create_group = $group_info[0]['created_by'];
		$querySix = "SELECT * FROM auth_user WHERE id = '$id_user_create_group'";
		$info_by_create = $sql->query($querySix);
		$create_by = $info_by_create[0]['first_name'] .' '. $info_by_create[0]['last_name'];

		#info permissions table
		$querySeven = "SELECT * FROM auth_permission";
		$table_permission = $sql->query($querySeven);
		
		#sending data to view
		$items_to_print = array();
		$items_to_print['group'] = $group_info[0];
		$items_to_print['users'] = $user_list;
		$items_to_print['table_permission'] = $table_permission;
		$items_to_print['create_by'] = $create_by;
		\xeki\html_manager::render('dashboard/group_view.html',$items_to_print);
	}else{
		\xeki\core::redirect('');
	}
	
}
