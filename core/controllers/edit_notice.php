<?php

#import modules
$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');
$csrf = \xeki\module_manager::import_module('csrf');
\xeki\html_manager::add_extra_data("csrf", $csrf->get_token_html());


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
    $idNotice = $vars[notice];

    #search notice 
    $queryOne = "SELECT * FROM notices WHERE notices.id = '$idNotice' ";
    $noticeResult = $sql->query($queryOne);

    $queryTwo = "SELECT * FROM company";
    $company = $sql->query($queryTwo);

    $queryThree = "SELECT * FROM auth_group";
    $groups = $sql->query($queryThree);


    #sending data to view
    $items_to_print=array();
    $items_to_print['notice']= $noticeResult[0];
    $items_to_print['business']= $company;
    $items_to_print['groups']= $groups;

    
    \xeki\html_manager::render('dashboard/notice_edit.html',$items_to_print);
}
