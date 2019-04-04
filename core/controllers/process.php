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
	$query = "SELECT * FROM transversal_process WHERE id = $id ORDER BY order_by ASC";
	$transversal_process = $sql->query($query);
	
	d($transversal_process);
	
	#sending data to view
	$items_to_print=array();
	$items_to_print['transversal_process'] = $transversal_process;

	
	\xeki\html_manager::render('process.html',$items_to_print);
}
