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

	#id transversal process
	$id = $_COOKIE["idProcess"];
	
	#sql
	$queryOne = "SELECT * FROM transversal_process WHERE id = $id ORDER BY order_by ASC";
	$transversal_process = $sql->query($queryOne);
	
	$queryTwo = "SELECT * FROM transversal_process_item WHERE idFather = $id ORDER BY order_by ASC";
	$transversal_process_item = $sql->query($queryTwo);

	
	#sending data to view
	$items_to_print=array();
	$items_to_print['transversal_process'] = $transversal_process;
	$items_to_print['banner'] = $transversal_process[0]['banner'];
	$items_to_print['title'] = $transversal_process[0]['name'];
	$items_to_print['transversal_process_item'] = $transversal_process_item;
	\xeki\html_manager::render('process.html',$items_to_print);
}
