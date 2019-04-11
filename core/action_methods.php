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
	setcookie("idProcess",$data['id'],time()+60000);
	\xeki\core::redirect('procesos');
});

//files
\xeki\routes::action('files', function(){
	$data = $_POST;
	setcookie("idFiles",$data['id'],time()+60000);
	\xeki\core::redirect('archivos');
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

//forgotpw
\xeki\routes::action('auth::forgotpw', function(){
	$csrf = \xeki\module_manager::import_module('csrf');
	$sql=\xeki\module_manager::import_module("db-sql");

	$valid_csrf = $csrf->validate_token();

	if($valid_csrf){
		$code = rand(1000, 9999);
		
		
		#search user
		$email = $_POST['email'];
		
		#sql
		$query = "SELECT * FROM auth_user WHERE email ='$email' ";
		$emailResponse = $sql->query($query);

		#set cookie id user
		setcookie("id_user_restorepw",$emailResponse[0]['id'],time()+60000);

		#insert code
		if($emailResponse){
			$data = array(
				'id_user' => $emailResponse[0]['id'],
				'code' => $code
			);
			$sql->insert("forgotpw_token", $data);
			\xeki\core::redirect('restaurar-clave-codigo');
		}else{
			d("dont search email");
		}

	}
	else{
		d("error csrf!");
	}	
});


//request code 
\xeki\routes::action('auth::forgotpw', function(){
	$sql=\xeki\module_manager::import_module("db-sql");

	#id user search
	$id = $_COOKIE["id_user_restorepw"];
	
	#sql
	$query = "SELECT * FROM forgotpw_token WHERE id_user ='$id' ORDER BY id DESC";
	$restore_pw = $sql->query($query);
	
});

//validate code 
\xeki\routes::action('auth::requestcode', function(){
	$sql=\xeki\module_manager::import_module("db-sql");

	#id user search
	$id = $_COOKIE["id_user_restorepw"];
	
	#sql
	$query = "SELECT * FROM forgotpw_token WHERE id_user ='$id' ORDER BY id DESC";
	$restore_pw = $sql->query($query);
	
	$code_database = $restore_pw[0]['code'];
	$code_user = $_POST['code'];
	$user_id = $restore_pw[0]['user_id'];
	
	if($code_user == $code_database){
		\xeki\core::redirect('nueva-clave');
	}else{
		\xeki\html_manager::add_extra_data("error_code","Código invalido");
	}
	
});


//update pw 
\xeki\routes::action('auth::updatepw', function(){
	$sql=\xeki\module_manager::import_module("db-sql");

	$data = $_POST;
	$user_id = $_COOKIE["id_user_restorepw"];

	if($data['password'] != $data['password_confirm']){
		\xeki\html_manager::add_extra_data("dont_match_pw","Las contraseñas no coinciden");
	}elseif($data['user_id'] != $user_id){
		\xeki\html_manager::add_extra_data("dont_match_user","Error de actualización, intenta nuevamente");
	}else{
		\xeki\html_manager::add_extra_data("update_password_successful","La contraseña se ha actualizado de manera éxitosa");


		$password = hash("sha256", $_POST['password']);
		$user_id = $_POST['user_id'];

		$query = "UPDATE auth_user SET auth_user.password = '$password' WHERE id = '$user_id' ";
		$update_data = $sql->query($query);		


		\xeki\core::redirect('');
	}


});