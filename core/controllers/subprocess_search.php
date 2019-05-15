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
	$process_id =	$vars[process];
	$item_id = $vars[item];


	#sql
	$queryOne = "SELECT * FROM transversal_process_item WHERE id = $item_id AND father_id = $process_id ";
	$process = $sql->query($queryOne);
	d($process);

	$queryTwo = "SELECT * FROM transversal_process_subitem WHERE father_id = $item_id ";
	$items = $sql->query($queryTwo);
	d($items);
	#sending data to view
	$items_to_print=array();
	$items_to_print['item'] = $item;
	$items_to_print['process'] = $process;
	\xeki\html_manager::render('dashboard/subprocess_item.html',$items_to_print);
}
