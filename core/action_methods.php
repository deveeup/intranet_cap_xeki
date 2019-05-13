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
			setcookie("update_password_successful",false,time()+1);
		} elseif ($user->code == 'not_user_exit') {
			\xeki\html_manager::add_extra_data("error_login","Usuario no existe");
			setcookie("update_password_successful",false,time()+1);
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
			\xeki\html_manager::add_extra_data("dont_search_email","No tenemos registro de este correo electrónico");
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
	
	$data = $_POST;
	$user_id = $_COOKIE["id_user_restorepw"];
	
	if($data['password'] != $data['password_confirm']){
		\xeki\html_manager::add_extra_data("dont_match_pw","Las contraseñas no coinciden");
	}elseif($data['user_id'] != $user_id){
		\xeki\html_manager::add_extra_data("dont_match_user","Error de actualización, intenta nuevamente");
	}else{
		#import module
		$sql=\xeki\module_manager::import_module("db-sql");

		#drop data from
		$password = hash("sha256", $_POST['password']);
		$user_id = $_POST['user_id'];

		#update data in database
		$query = "UPDATE auth_user SET auth_user.password = '$password' WHERE id = '$user_id' ";
		$update_data = $sql->query($query);		

		#set var for message
		setcookie("update_password_successful",true,time()+3);

		\xeki\core::redirect('');
	}

});

//update user (by user admin)
\xeki\routes::action('auth::update_rol', function(){
	#import module
	$sql=\xeki\module_manager::import_module("db-sql");

	#drop data
	$user_id = $_POST['id'];
	$is_active = $_POST['is_active'];
	$is_superuser = $_POST['is_superuser'];
	$url = $_POST['url'];

	$data_update = [
		'is_active'=>$is_active,
		'is_superuser'=>$is_superuser,
	];
	#
	$update_db = $sql->update("auth_user", $data_update, "id = $user_id");
	if($update_db){
		\xeki\html_manager::add_extra_data("update_user_rol","Usuario actualizado con éxito");
	}else {
		\xeki\html_manager::add_extra_data("update_user_rol_error","Ocurrió un error, intenta nuevamente");
	}

});

//add user > group (by user admin)
\xeki\routes::action('auth::add_group', function(){
	#import module
	$auth = \xeki\module_manager::import_module('auth');
	
	#drop data
	$user = $auth->get_user(); 
	$user_id = $_POST['id_user'];
	$id_group = $_POST['id_group'];

	$add_user_group = $user->group_add_by_id($id_group, $user_id);

	if($add_user_group){
		\xeki\html_manager::add_extra_data("add_user_group","El usuario se ha agregado al grupo");
	}else{
		\xeki\html_manager::add_extra_data("add_user_group_error","Ocurrió un error, intenta nuevamente");
	}

});

//update pw (by user admin)
\xeki\routes::action('auth::updatepw_user', function(){
	
	$data = $_POST;
	
	if($data['password'] != $data['password_confirm']){
		\xeki\html_manager::add_extra_data("dont_match_pw_user","Las contraseñas no coinciden");
	}else{
		#import module
		$sql=\xeki\module_manager::import_module("db-sql");

		#drop data from
		$password = hash("sha256", $_POST['password']);
		$user_id = $_POST['id_user'];

		#update data in database
		$query = "UPDATE auth_user SET auth_user.password = '$password' WHERE id = '$user_id' ";
		$update_data = $sql->query($query);		
		#set var for message
		\xeki\html_manager::add_extra_data("password_update_successful_user","La contraseña se ha actualizado");
	}

});

//delete group (by user admin)
\xeki\routes::action('auth::action_groups', function(){
	#import module
	$sql=\xeki\module_manager::import_module("db-sql");
	$csrf = \xeki\module_manager::import_module('csrf');

	#vars 
	$action =	$_POST['action'];
	$id_group = $_POST['group_id'];
	#validate token 
	$valid_csrf = $csrf->validate_token();

	if($valid_csrf) {
		switch ($action) {
			case "view":
				#set var for message
				setcookie("select_group_view",true,time()+3);
				\xeki\core::redirect('');
				break;
			case "delete":
				#search users in group 
				$query = "SELECT * FROM auth_user_group WHERE group_ref ='$id_group' ";
				$response = $sql->query($query);
				if($response){
						\xeki\html_manager::add_extra_data("action_group_delete_fail","No se puede eliminar el grupo, debido a que contiene usuarios.");
				}else {
					$delete_group = $sql->delete("auth_group", "id = $id_group");
					if($delete_group){
						\xeki\html_manager::add_extra_data("action_group_delete_done","El grupo se ha eliminado correctamente.");
					}else {
						\xeki\html_manager::add_extra_data("action_group_delete_fail","Ha ocurrido un error.");
					}
				}
				break;
		}
	}else {
		#error csrf
	}
});

//update group (by user admin)
\xeki\routes::action('auth::update_group', function(){
	#import module
	$sql=\xeki\module_manager::import_module("db-sql");
	$csrf = \xeki\module_manager::import_module('csrf');

	#vars 
	$name =	$_POST['name'];
	$last_name =	$_POST['last_name'];
	$id_group = $_POST['group_id'];
	$id_user = $_POST['id_user'];
	#validate token 
	$valid_csrf = $csrf->validate_token();

	if($valid_csrf) {
		#update group 
		$query = "UPDATE auth_group  SET auth_group.name = '$name', auth_group.previous_name = '$last_name', auth_group.modified_by = '$id_user' WHERE auth_group.id ='$id_group' ";
		$response = $sql->query($query);
		\xeki\html_manager::add_extra_data("action_group_delete_done","El grupo se ha actualizado correctamente.");
	}else {
		#error csrf
	}
});

//update group (by user admin)
\xeki\routes::action('auth::update_group_user', function(){
	#import module
	$sql=\xeki\module_manager::import_module("db-sql");
	$csrf = \xeki\module_manager::import_module('csrf');

	#vars 
	$permission = $_POST['permission'];
	$id_user = $_POST['id_user'];
	$id_group = $_POST['id_group'];
	// d($_POST);
	
	$valid_csrf = $csrf->validate_token();
	if($valid_csrf) {
		#update group 
		$queryOne = "SELECT * FROM auth_user_group WHERE auth_user_group.user_ref = '$id_user' AND auth_user_group.group_ref = '$id_group' ";
		$validateUserInGroup = $sql->query($queryOne);

		if($validateUserInGroup){
			$queryTwo = "SELECT * FROM auth_user_permission WHERE auth_user_permission.user_ref = '$id_user' AND auth_user_permission.group_ref = '$id_group' ";
			$validatePermissionUser = $sql->query($queryTwo);
			if($validatePermissionUser[0]['permission_ref'] == 3 AND $permission == 3){
				\xeki\html_manager::add_extra_data("message_user_adm","Este usuario ya tiene permisos de administrador.");
			}elseif ($validatePermissionUser[0]['permission_ref'] == $permission) {
				\xeki\html_manager::add_extra_data("message_user_error","Este usuario ya tiene este permiso.");
			}else {
				
				#validar si es update o insert
				$queryTwo = "SELECT * FROM auth_user_permission WHERE auth_user_permission.user_ref = '$id_user' AND auth_user_permission.group_ref = '$id_group' ";
				$validatePermissionUser = $sql->query($queryTwo);
				if($validatePermissionUser){
					#update
					$queryThree = "UPDATE auth_user_permission SET auth_user_permission.permission_ref = '$permission' WHERE auth_user_permission.user_ref = '$id_user' AND auth_user_permission.group_ref = '$id_group' ";
					$responseUpdate = $sql->query($queryThree);
					\xeki\html_manager::add_extra_data("message_user_successful","Usuario actualizado con éxito.");
				}else{
					#insert
					$data = array(
						'user_ref' => $id_user,
						'permission_ref' => $permission,
						'group_ref' => $id_group
					);
					$insertUser =	$sql->insert("auth_user_permission", $data);
					if($insertUser){
						\xeki\html_manager::add_extra_data("message_user_successful","Usuario actualizado con éxito.");
					}else{
						\xeki\html_manager::add_extra_data("message_user_error","Ha ocurrido un error al actualizar el usuario.");
					}
				}
			}
		}
		else {
			#el usuario no pertenece al grupo, no puede ser adm
		}
	}else {
		#error csrf
	}
	
});

//update group (by user admin)
\xeki\routes::action('auth::update_permission_user', function(){
	#import module
	$sql = \xeki\module_manager::import_module("db-sql");
	$csrf = \xeki\module_manager::import_module('csrf');

	#vars 
	$id_group = $_POST['id_group'];
	$id_user = $_POST['id_user'];

	#validate token 
	$valid_csrf = $csrf->validate_token();

	if($valid_csrf) {
		#update group 
		$queryOne = "SELECT * FROM auth_user_permission WHERE auth_user_permission.user_ref = '$id_user' AND auth_user_permission.group_ref = '$id_group' ";
		$validateUserPermission = $sql->query($queryOne);
		if($validateUserPermission){
			$queryDelete = "DELETE FROM auth_user_permission WHERE auth_user_permission.user_ref = '$id_user' AND auth_user_permission.group_ref = '$id_group' ";
			$response = $sql->query($queryDelete);
			\xeki\html_manager::add_extra_data("message_user_successful","Se ha revocado el permiso al usuario de manera exitosa.");
		}else{
			\xeki\html_manager::add_extra_data("message_user_error","Ocurrió un error revocando el permiso al usuario.");
		}
		
	}else {
		#error csrf
	}
});

//create_user (by user admin)
\xeki\routes::action('auth::create_user', function(){
	#import module
	$csrf = \xeki\module_manager::import_module('csrf');
	$auth = \xeki\module_manager::import_module('auth');

	#global data 
	$data = $_POST;
	#login data
	$email = $data["email"];
	$password = $data["password"];
	#more data
	$additional_data = [
		"first_name" => $data["first_name"],
		"last_name" => $data["last_name"],
		"username" => $data["username"],
		"position" => $data["position"],
		"company" => $data["company"],
		"city" => $data["city"],
		"is_superuser" => "no",
		"is_staff" => "no",
		"is_active" => "yes",
	];

	#validate token 
	$valid_csrf = $csrf->validate_token();

	if($valid_csrf) {
		$response = $auth->create_user($email,$password,$additional_data);
		if($response){
			\xeki\html_manager::add_extra_data("message_user_create","El usuario se ha creado de manera correcta.");
		}else {
			\xeki\html_manager::add_extra_data("message_user_create_error","Ocurrió un error creando el usuario.");
		}
	}else {
		#error csrf
	}
});

//create_group (by user admin)
\xeki\routes::action('auth::create_group', function(){
	#import module
	$sql = \xeki\module_manager::import_module("db-sql");
	$csrf = \xeki\module_manager::import_module('csrf');

	#validate token 
	$valid_csrf = $csrf->validate_token();

	if($valid_csrf) {
		$data = array(
			'code' =>$$_POST["code"],
			'name' => $_POST["name"],
			'created_by' => $_POST["id_user"]
		);
		$insert = $sql->insert("auth_group", $data);
		if($insert){
			\xeki\html_manager::add_extra_data("message_group_create","El grupo se ha creado de manera correcta.");
		}else {
			\xeki\html_manager::add_extra_data("message_group_create_error","Ocurrió un error creando el grupo.");
		}
	}else {
		#error csrf
	}
});

//delete user (by user admin)
\xeki\routes::action('auth::delete_user', function(){
	#import module
	$sql = \xeki\module_manager::import_module("db-sql");
	$csrf = \xeki\module_manager::import_module('csrf');

	#validate token 
	$valid_csrf = $csrf->validate_token();

	if($valid_csrf) {
		$id_user = $_POST["id_user"]; 

		$delete_user = $sql->delete("auth_user", "id = $id_user");
		$delete_user_group = $sql->delete("auth_user_group", "user_ref = $id_user");
		$delete_user_permission = $sql->delete("auth_user_permission", "user_ref = $id_user");

		if($delete_user){
			\xeki\core::redirect('panel/usuarios');
		}else {
			# error 
		}
	}else {
		#error csrf
	}
});

//update city (by user admin)
\xeki\routes::action('auth::update_city', function(){
	#import module
	$sql = \xeki\module_manager::import_module("db-sql");
	$csrf = \xeki\module_manager::import_module('csrf');

	#validate token 
	$valid_csrf = $csrf->validate_token();

	$name = $_POST['name'];
	$id_city = $_POST['id_city'];
	$id_user = $_POST['id_user'];
	if($valid_csrf) {
		$queryOne = "UPDATE cities SET cities.name = '$name', cities.modified_by = '$id_user'  WHERE  cities.id = '$id_city' ";
		$responseUpdate = $sql->query($queryOne);
		\xeki\html_manager::add_extra_data("update_city","La ciudad se ha actualizado con éxito.");
	}else{
		#error csrf
	}
});