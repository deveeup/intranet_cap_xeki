<?php

#import modules
$sql=\xeki\module_manager::import_module("db-sql");
$auth = \xeki\module_manager::import_module('auth');
$mail =  \xeki\module_manager::import_module('xeki_mail');

#validate logged
if(!$auth->is_logged()){
	\xeki\core::redirect('');
} else {
	#email 
		$to = "jossedaviid1@gmail.com";
		$subject = "Confirmacion Contacto";
		$path = dirname(__FILE__)."/pages/mail/"."_contactMail.html";
		d($path);
		$html = file_get_contents($path); // like top email body
		$array_info = array(
			"email_from" => "info@xeki.com", #opcional
			// example info
			"codes_html" =>$codes_html,
			'name' => $vl_values['name'],
			'email' => $vl_values['email'],
			'subject' => $vl_values['subject'],
			'message' => $vl_values['message'],
		);
		$mail->send_email($to,$subject,$html,$array_info);


	#info seo
	$title = "Intranet";
	$description = "Lorem...";
	$keyworkds= "Funeraria, Coorserpark, Capillas de La Fe, capillas la fe, obituarios, sedes";
	\xeki\html_manager::set_seo($title,$description,false);


	#info user
	$user = $auth->get_user(); 
	$data['user'] = $user->get_info();
	#info group
	$id_user = $user->get("id");
	$query_search_notice = "SELECT group_ref FROM auth_user_group WHERE user_ref = '$id_user' ";
	$query_notice = $sql->query($query_search_notice);
	$group_user = $query_notice[0]['group_ref'];
	$for_company = $user->get("company");
	#query notices
	$notices = "SELECT * FROM notices WHERE group_ref = '$group_user' AND for_company = '$for_company' OR for_company = '0' OR  group_ref = '0' AND for_company = '$for_company' ";
	$notices_data = $sql->query($notices);

	#query agreements 
	$query_search_agreements = "SELECT * FROM agreements";
	$query_agreements = $sql->query($query_search_agreements);

	#query inductions 
	$query_search_induction = "SELECT * FROM induction";
	$query_induction = $sql->query($query_search_induction);

	#sending data to view
	$items_to_print = array();
	$items_to_print['user'] = $data['user'];
	$items_to_print['notices'] = $notices_data;
	$items_to_print['agreements'] = $query_agreements[0];
	$items_to_print['inductions'] = $query_induction[0];

	
	\xeki\html_manager::render('index.html',$items_to_print);
}
