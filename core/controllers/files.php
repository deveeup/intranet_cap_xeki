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
	$id = $_COOKIE["idFiles"];
		
	#sql
	$queryOne = "SELECT * FROM transversal_process_item WHERE id = $id";
	$transversal_process_item = $sql->query($queryOne);
	
	
	$queryTwo = "SELECT * FROM transversal_process_subitem WHERE father_id = $id ORDER BY order_by ASC";
	$transversal_process_subitem = $sql->query($queryTwo);
	

	#sending data to view
	$items_to_print=array();
	$items_to_print['transversal_process_item'] = $transversal_process_item;
	$items_to_print['title'] = $transversal_process_item[0]['name'];
	$items_to_print['icon'] = $transversal_process_item[0]['icon'];
	$items_to_print['transversal_process_subitem'] = $transversal_process_subitem;
	\xeki\html_manager::render('files.html',$items_to_print);
}
