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
	
	#select all process
	$queryOne = "SELECT * FROM transversal_process";
	$transversal_process = $sql->query($queryOne);
	
	$item_process = array();
	foreach ($transversal_process as $process){

		$queryTwo = "SELECT * FROM transversal_process_item WHERE transversal_process_item.father_id = '$process[id]' ";
		$item_process = $sql->query($queryTwo);

		$process[items] = array();

		foreach ($item_process as $item){
			if($process['id'] == $item['father_id']){
				array_push($process[items], $item);
			}
		}
		d($process);
	}
	
	// $queryTwo = "SELECT * FROM transversal_process_item WHERE father_id = $id ORDER BY order_by ASC";
	// $transversal_process_item = $sql->query($queryTwo);
	// d($transversal_process_item);


	
	#sending data to view
	$items_to_print=array();
	$items_to_print['transversal_process'] = $transversal_process;
	\xeki\html_manager::render('dashboard/process.html', $items_to_print);
}
