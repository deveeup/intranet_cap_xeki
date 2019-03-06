<?php

require_once dirname(__FILE__).'/../_common/sql_lib.php';
require_once dirname(__FILE__).'/../../libs/xeki_util_methods.php';
require_once dirname(__FILE__).'/../../libs/xeki_core/module_manager.php';

## get main number of config db
$sql=\xeki\module_manager::import_module("db-sql","main");


// user permissions
$user_permissions_table = array(
    'table' => 'user_permissions',
    'elements' => array(
        'code' => 'text:NN:n:true:true:Code',
        'name' => 'text:NN:n:true:true:Name',
    ),
);
createSqlxeki_v1($user_permissions_table,$sql);


$user_namespace_table = array(
    'table' => 'user_namespace',
    'elements' => array(
        'code' => 'text:NN:n:true:true:Code',
        'name' => 'text:NN:n:true:true:Name',
    ),
);
createSqlxeki_v1($user_namespace_table,$sql);


// user
$user_table = array(
    'elements' => array(
        'name' => 'text:NN:n:true:true:Name',
        'last_name' => 'text:NN:n:true:true:Name Last',
        'phone' => 'text:NN:n:true:true:Telefono',
        'separator:Basic Auth',
        'id' => 'text:NN:n:true:true:Id',
        'email' => 'text:NN:n:true:true:Email',
        'password' => 'text:NN:n:true:true:Password',
        'recover_code' => 'text:NN:n:true:true:Recover Code',
        'confirm_code' => 'text:NN:n:true:true:Recover Code',
        'activated' => 'text:NN:n:true:true:Recover Code',
        'identifier_id' => 'text:NN:n:true:true:Country Id',
        'idFacebook' => 'text:NN:n:true:true:Country Id',
        'photo' => 'text:NN:n:true:true:Country Id',
        'gender' => 'text:NN:n:true:true:Country Id',
        'number_friends' => 'text:NN:n:true:true:Country Id',




        'xeki_auth_state_user' => 'text:NN:n:true:true:state_user', ## for partial and complete user
    ),
);
createSqlxeki_v1($user_table,$sql);


$user_permissions_table_ref = array(
    'table' => 'user_permissions_table_ref',
    'elements' => array(
        'user_id' => 'number:NN:n:true:true:User ID',
        'user_permissions_id' => 'number:NN:n:true:true:Name',
    ),
);
createSqlxeki_v1($user_permissions_table_ref,$sql);

$user_namespace_table_ref = array(
    'table' => 'user_namespace_table_ref',
    'elements' => array(
        'user_id' => 'number:NN:n:true:true:User ID',
        'user_namespace_id' => 'number:NN:n:true:true:Name',
    ),
);
createSqlxeki_v1($user_namespace_table_ref,$sql);

// user_permissions

// user_permissions_ref