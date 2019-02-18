<?php
## methods post
## recibe action post in xeki

//d("yes im running");
 \xeki\routes::action('xeki_auth::check_by_user', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');
    $bool_check = $xeki_auth->check_by_user($_POST['user']);
    if ($bool_check){
        if($_POST['url_post_login'])$xeki_auth->go_to_login($_POST['url_post_login']);
        else $xeki_auth->go_to_login();
        }
    else{

        if($_POST['url_post_login'])$xeki_auth->go_to_register($_POST['url_post_login']);
        else $xeki_auth->go_to_register();
        }
});
 \xeki\routes::action('xeki_auth::resubmit_confirm', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');
    $info = $xeki_auth->get_user_info();
    $email=$info['email'];
    $code=$info['confirm_code'];

    $xeki_auth->send_email_confirm_account($email,$code,$info);


});
 \xeki\routes::action('xeki_auth::login', function(){
     
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');

    if(!isset($_POST['xeki_next_page']))
        $xeki_auth->set_logged_page($_POST['xeki_next_page']);


    if(!isset($_POST['user_identifier']))
        \xeki\core::fatal_error("Not found user_identifier on post request");

    if(!isset($_POST['user_pass']))
        \xeki\core::fatal_error("Not found user_pass on form");

    $xeki_auth->login($_POST['user_identifier'], $_POST['user_pass']);

    // solo pasa si no se logea
    $message_popup = $xeki_auth->get_value_param("msg_no_valid_user");
    $popUp->add_msg( $message_popup );

});

\xeki\routes::action('xeki_auth::register', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');

    ## check if email is register
    if(!isset($_POST['email'])){
        if(isset($_POST['user_identifier']))$_POST['email']=$_POST['user_identifier'];
    }

    # no password partial register
    if(!isset($_POST['user_pass'])){
        $_POST['user_pass']="password_ag_user";
        $_POST['tp_xeki_auth_state_user']="partial";
    }
    else{
        $_POST['tp_xeki_auth_state_user']="complete";
    }

    // valid

    if(!isset($_POST['xeki_next_page'])){
        $xeki_auth->set_logged_page($_POST['xeki_next_page']);
    }

    // create extra data from form
    $extra_data= array();
    foreach ($_POST as $key=>$value){
        if (strpos($key, 'tp_') !== false) {
            $key_fix = str_replace('tp_','',$key);
            $extra_data[$key_fix]=$value;
        }
    }

    ## valid if user exist

    $query = "SELECT * FROM " . $xeki_auth->getTableUser() . " WHERE " . $xeki_auth->getFieldUser() . "='" . $_POST['email'] . "'";
    $res = $sql->query($query);

    if (count($res) == 0) {
        $res = $xeki_auth->secure_register($_POST['email'], $_POST['user_pass'], $extra_data);
        $xeki_auth->login($_POST['email'], $_POST['password']);


        $message_popup = $xeki_auth->get_value_param("msg_new_user");
        $popUp->add_msg( $message_popup );
    } else
     {

        $popUp->add_msg("Ya existe una cuenta asociada a este correo.");
        $message_popup = $xeki_auth->get_value_param("msg_user_exist");
        $popUp->add_msg( $message_popup );

        // TODO re do partial register
    }
});

## ---------------------------------
## Update Data
## ---------------------------------

\xeki\routes::action('xeki_auth::update_edit', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');
    $data = array(
    'name' => limpiarCadena($_POST['name']),
    'lastName' => limpiarCadena($_POST['lastName']),
    'cellphone' => limpiarCadena($_POST['cellphone']),
    'birthDate' => limpiarCadena($_POST['birthDate']),
    );
    $USER = $xeki_auth->getUserInfo();;

    $res = $sql->update($xeki_auth->getTableUser(), $data, 'id="' . $USER["id"] . '"');
    $xeki_auth->updateUserInfo();
    //    ag_pushMsg('Tu cuenta ha sido actualiada :)');
    // echo '<meta http-equiv="refresh" content="0">';
});

## ---------------------------------
## Update Data Pass
## ---------------------------------

\xeki\routes::action('xeki_auth::register_pass', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');
    $data = array(
        'password' => $xeki_auth->encrypt($_POST['user_pass']),
        'xeki_auth_state_user' =>"complete",
    );
    $USER = $xeki_auth->getUserInfo();
    $res = $sql->update($xeki_auth->getTableUser(), $data, 'id="' . $USER["id"] . '"');

    $xeki_auth->updateUserInfo();
    if(isset($_POST['xeki_NEXT_PAGE'])){
        \xeki\core::redirect($_POST['xeki_NEXT_PAGE']);
    }

    //    ag_pushMsg('Tu contraseña ha sido actualizada');
});

\xeki\routes::action('xeki_auth::update_edit_pass', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');
    $data = array(
        'password' => encrypt($_POST['password']),
    );
    $USER = $xeki_auth->getUserInfo();
    $res = $sql->update($xeki_auth->getTableUser(), $data, 'id="' . $USER["id"] . '"');
    //    ag_pushMsg('Tu contraseña ha sido actualizada');
    $popUp->add_msg("Tu contraseña ha sido actualizada.");
});

\xeki\routes::action('xeki_auth::recover_pass_update', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');
    $response = $xeki_auth->set_pass_by_code_recover($_SESSION['recover_code'],$_POST['user_pass']);
    //    ag_pushMsg('Tu contraseña ha sido actualizada');

    if($response){
        $popUp->add_msg("Tu contraseña ha sido actualizada.");
    }
    else{
        $popUp->add_msg("El codigo de actualizacion ya no es valido, intenta recuperar de nuevo.");
    }
});

## ---------------------------------
## Set Recover pass
## ---------------------------------

\xeki\routes::action('xeki_auth::recover_pass', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');

    $res = $xeki_auth->set_code_recover($_POST['email']);

    $mail = \xeki\module_manager::import_module("xeki_mail");

  	$recover_pass_mail_route = $xeki_auth->get_value_param("mail_recover_pass_route");

  	$path_file = dirname(__FILE__) . "/../../../$recover_pass_mail_route";

  	if(!file_exists($path_file)){
  		$path_file = dirname(__FILE__)."/pages/mail/recover_pass.html";
  	}

    // TODO mejorar esto
    // get ifo user
    $info_to_email = $xeki_auth->get_info_by_email($_POST['email']);
      //d($_POST);
      //d($info_to_email );
      //die();
    $content = file_get_contents($path_file);
    //    d($AG_BASE_COMPLETE.$AG_L_PARAM.'/'.$res);
    $url_base =\xeki\core::$URL_BASE_COMPLETE;
    $AG_L_PARAM =\xeki\core::$URL_PARAMS_LAST;
    $info_to_email['url']=$url_base.$AG_L_PARAM.'/'.$res;
    //d($info_to_email);
    //d($content);
    //die();
    $mail->send_email($_POST['email'],"Recover Pass",$content,$info_to_email);
    //    ag_pushMsg('Tu contraseña ha sido actualizada');
    $popUp->add_msg("Se ha enviado a tu correo las instrucciones.");
});

\xeki\routes::action('xeki_auth::confirm_account_resend_email', function(){
    $sql = \xeki\module_manager::import_module('xeki_db_sql', 'main');
    $xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');
    $popUp =  \xeki\module_manager::import_module('xeki_popup');

});