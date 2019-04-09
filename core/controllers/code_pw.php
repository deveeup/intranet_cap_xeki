<?php

#import modules
$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');



	#info seo
	$title = "Intranet";
	$description = "Lorem...";
	$keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
	\xeki\html_manager::set_seo($title,$description,false);

	#id transversal process
	$id = $_COOKIE["id_user_restorepw"];
	
	#sql
	$query = "SELECT * FROM forgotpw_token WHERE id_user ='$id' ORDER BY id DESC";
	$restore_pw = $sql->query($query);
	
	// d($restore_pw[0]['code']);
	
	#sending data to view
	$items_to_print=array();
	$items_to_print['transversal_process'] = $transversal_process;
	$items_to_print['banner'] = $transversal_process[0]['banner'];
	$items_to_print['title'] = $transversal_process[0]['name'];
	$items_to_print['transversal_process_item'] = $transversal_process_item;
	\xeki\html_manager::render('login/request-code.html',$items_to_print);