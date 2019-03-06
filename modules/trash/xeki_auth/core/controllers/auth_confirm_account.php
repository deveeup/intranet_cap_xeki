<?php
/**
 * Created by PhpStorm.
 * User: liusp
 * Date: 4/10/2016
 * Time: 6:13 PM
 */

//echo "hi!!";
$sql = $AG_MODULES->ag_module_import('xeki_db_sql', 'main');
$xeki_auth = \xeki\module_manager::import_module('xeki_auth', 'main');

//d($sql->query("SELECT now()"));
//
//d($AG_MODULES);
//d($AG_HTML);
//d($AG_MODULES);
$array_data = array();
//d($AG_PARAMS);
//d(count($AG_PARAMS));

if(count($AG_PARAMS)==2){
    // confirm
    $query="SELECT * FROM user where confirm_code='{$AG_PARAMS[1]}'";
    $res = $sql->query($query);
    if(count($res)>0){
        // update
        $res=$res[0];
//        d($res);
        $data= array(
            "confirm_code"=>"",
            "activated"=>"on",
        );
        $sql->update("user",$data," id = '{$res['id']}' ");
        $xeki_popup =  \xeki\module_manager::import_module('xeki_popup');
        $xeki_popup->add_msg("Usuario activado");
        \xeki\core::redirect("login");
    }
    else{
        // xeki_popup
        // redirect to home
    }

}
else{
    // check if is logged
    $user_zone = \xeki\module_manager::import_module('xeki_auth');
    $folder_auth=$user_zone->get_folder();
    \xeki\html_manager::render("{$folder_auth}/auth_confirm_account.html", $array_data);
}
//

