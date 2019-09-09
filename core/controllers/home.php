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
		// $to = "jossedaviid1@gmail.com";
		// $subject = "Confirmacion Contacto";
		// $path = dirname(__FILE__)."/pages/mail/"."_contactMail.html";
		// d($path);
		// $html = file_get_contents($path); // like top email body
		// $array_info = array(
		// 	"email_from" => "info@xeki.com", #opcional
		// 	// example info
		// 	"codes_html" =>$codes_html,
		// 	'name' => $vl_values['name'],
		// 	'email' => $vl_values['email'],
		// 	'subject' => $vl_values['subject'],
		// 	'message' => $vl_values['message'],
		// );
		// $mail->send_email($to,$subject,$html,$array_info);

	
	\xeki\html_manager::render('index.html',$items_to_print);
}
