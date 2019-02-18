<?php
// url post
\xeki\routes::post('example/post/request', function(){
	$sql=\xeki\module_manager::import_module("xeki_db_sql");
	$_POST;
	\xeki\html_manager::$done_render=true;
});

// action for contact form
\xeki\routes::action('contacto', function(){
	// imports
		$sql=\xeki\module_manager::import_module("xeki_db_sql");
		$mail=\xeki\module_manager::import_module("xeki_mail");
		$popup=\xeki\module_manager::import_module("xeki_popup");

		$to = $_POST['email'];
		$subject = "Su solicitud de contacto ha sido recibida";
	
	// Read template email html 
		$path = \xeki\core::$SYSTEM_PATH_BASE."/core/pages/mail/_contactMail.html";
		$html = file_get_contents($path); // like top email body

	// Dinamic data for email replace {{name}} into html 
	$array_info = array(
		'name' => $_POST['name'],
		'email' => $_POST['email'],
		'phone' => $_POST['phone'],
		'mensaje' => $_POST['message'],
	);

	// Send email
	$mail->send_email($to,$subject,$html,$array_info);

	$popup->add_msg("Gracias por escribirnos su mensaje ha sido enviado.");
});