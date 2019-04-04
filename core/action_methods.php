<?php
// url post
\xeki\routes::post('example/post/request', function(){
	$sql=\xeki\module_manager::import_module("db-sql");
	$_POST;
	\xeki\html_manager::$done_render=true;
});

//upload user
\xeki\routes::action('upload-user', function(){
	$csrf = \xeki\module_manager::import_module('csrf');
	$valid_csrf = $csrf->validate_token();

	if($valid_csrf){
		$auth = \xeki\module_manager::import_module('auth');
		$user = $auth->get_user(); 
		$data = $_POST;
		$data_update = [
			'username'=>$data['username'],
			'university'=>$data['university'],
			'career'=>$data['career'],
			'description'=>$data['description'],
		];
		$user->update($data_update);
		\xeki\core::redirect('edit_profile');
	}
	else{
		//window modal error.
	}
});
//transversal process
\xeki\routes::action('process', function(){
	
	$data = $_POST;
	setcookie("idProcess",$data['id'],time()+5);
	\xeki\core::redirect('procesos');

});





// action for contact form
\xeki\routes::action('contacto', function(){
	// imports
		$sql=\xeki\module_manager::import_module("db-sql");
		$mail=\xeki\module_manager::import_module("xeki_mail");
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
});

//login
\xeki\routes::action('auth::login', function(){
	$csrf = \xeki\module_manager::import_module('csrf');
	$valid_csrf = $csrf->validate_token();
	if($valid_csrf){
		$auth = \xeki\module_manager::import_module('auth');
		$user = $auth->login($_POST['email'],$_POST['pw']);
		if($user->code == 'invalid_pass'){
			\xeki\html_manager::add_extra_data("error_login","Contraseña incorrecta");
		} elseif ($user->code == 'not_user_exit') {
			\xeki\html_manager::add_extra_data("error_login","Usuario no existe");
		} else{
			\xeki\core::redirect('inicio');
		}
	}
	else{
		d("error csrf!");
	}
});