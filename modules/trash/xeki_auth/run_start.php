<?php
/**
 * Created by PhpStorm.
 * User: Liuspatt
 * Date: 3/10/2016
 * Time: 11:42 PM
 */

$user = \xeki\module_manager::import_module("xeki_auth");

$info_user=$user->getUserInfo();
$info_user['xeki_auth']=array();
$info_user['xeki_auth']['logged']=$user->isLogged();
$info_user['xeki_auth']['user_info']=$user->getUserInfo();
//d($info_user);
\xeki\html_manager::add_extra_data($info_user);


//d("I RUN");