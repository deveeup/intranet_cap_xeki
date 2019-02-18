<?php
d ('hi ');
require_once dirname(__FILE__).'/../_common/sql_lib.php';
require_once dirname(__FILE__).'/../../libs/xeki_util_methods.php';
require_once dirname(__FILE__).'/../../libs/xeki_core/module_manager.php';

## get main number of config db
$sql=\xeki\module_manager::import_module("xeki_db_sql","main");
// user
$user_table = array(
    'table' => 'xeki_admin_user',
    'elements' => array(
        'name' => 'text:NN:n:true:true:Name',
        'lastName' => 'text:NN:n:true:true:Last',
        'phone' => 'text:NN:n:true:true:Telefono',
        'separator:Basic Auth',
        'email' => 'text:NN:n:true:true:Email',
        'password' => 'text:NN:n:true:true:Password',
        'recover_code' => 'text:NN:n:true:true:Recover Code',
        'xeki_userzone_state_user' => 'text:NN:n:true:true:state_user', ## for partial and complete user
    ),
);
createSqlxeki_v1($user_table,$sql);

// user_permissions

// user_permissions_ref