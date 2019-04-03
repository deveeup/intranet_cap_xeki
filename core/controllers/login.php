<?php

$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');

if(!$auth->is_logged()){
  $title = "Intranet";
  $description = "Lorem...";
  $keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
  \xeki\html_manager::set_seo($title,$description,false);
  
  // d($sql_info);
  $items_to_print=array();
  $items_to_print['info_array']=$sql_info;
  
  \xeki\html_manager::render('login/index.html',$items_to_print);
}else {
  \xeki\core::redirect('inicio');
}

