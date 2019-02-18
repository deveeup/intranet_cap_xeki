<?php

require_once(dirname(__FILE__) . "/../libs/vendor/facebook/graph-sdk/src/Facebook/autoload.php");

$user_zone = \xeki\module_manager::import_module('xeki_auth');

$AG_BASE_COMPLETE = \xeki\html_manager::get_url_base_complete();

$facebook_app_id = $user_zone->get_value_param("facebook_app_id");
$facebook_app_secret = $user_zone->get_value_param("facebook_app_secret");



$facebook_app_call_back_url = $AG_BASE_COMPLETE.$user_zone->get_value_param("facebook_call_back_url");

$fb = new Facebook\Facebook([
    'default_graph_version' => 'v2.10',
    'app_id' => "$facebook_app_id",
    'app_secret' => "$facebook_app_secret",
//'default_access_token' => '{access-token}', // optional
]);

$helper = $fb->getPageTabHelper();

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'public_profile', 'user_friends']; // optional
$loginUrl = $helper->getLoginUrl("$facebook_app_call_back_url", $permissions);


# debug 
// d($AG_BASE_COMPLETE);
// d($facebook_app_id);
// d($facebook_app_secret);
// d($facebook_app_call_back_url);
// die();
\xeki\core::redirect($loginUrl);