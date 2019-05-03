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
	
	#sending data to view
	$items_to_print = array();
	$items_to_print['user'] = $array_user[0];
	\xeki\html_manager::render('view_user.html', $items_to_print);
	}
	
