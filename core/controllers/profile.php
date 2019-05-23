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
    
    #info user
    $user = $auth->get_user(); 
    $data['user'] = $user->get_info();
    $cityNumber = $data['user']['city'];
    #info city 
    $queryOne = "SELECT * FROM cities WHERE cities.id = '$cityNumber' order by id";
    $info_city = $sql->query($queryOne);
    #sending data to view
    $items_to_print=array();
    $items_to_print['user']= $data['user'];
    $items_to_print['city']= $info_city[0];
    
    \xeki\html_manager::render('profile.html',$items_to_print);
}
