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

	#sql
	// $query = "SELECT * FROM transversal_process order by order_by asc";
	// $transversal_process = $sql->query($query);

	#info user
	$user = $auth->get_user(); 
	$data['user'] = $user->get_info();
	
	#sending data to view
	$items_to_print = array();
	$items_to_print['user'] = $data['user'];
	// $items_to_print['transversal_process'] = $transversal_process;

	
	\xeki\html_manager::render('index.html',$items_to_print);
}
