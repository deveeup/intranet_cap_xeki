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
	
	$items_process = array();

	foreach ($transversal_process as $process){
		$queryTwo = "SELECT * FROM transversal_process_item WHERE transversal_process_item.father_id = '$process[id]' ";
		$item_process = $sql->query($queryTwo);
		$process[items] = array();
		foreach ($item_process as $item){
			// if($process['id'] == $item['father_id']){
			// 	array_push($process[items], $item);
			// }
			array_push($items_process, $item);
		}
	}
	
	#sending data to view
	$items_to_print=array();
	$items_to_print['transversal_process'] = $transversal_process;
	$items_to_print['items'] = $items_process;
	\xeki\html_manager::render('dashboard/process.html', $items_to_print);
}
