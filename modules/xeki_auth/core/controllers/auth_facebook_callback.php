<?php
$xeki_auth = \xeki\module_manager::import_module('xeki_auth');
$sql= \xeki\module_manager::import_module('xeki_db_sql');

$AG_BASE_COMPLETE = \xeki\html_manager::get_url_base_complete();

$facebook_app_id = $xeki_auth->get_value_param("facebook_app_id");
$facebook_app_secret = $xeki_auth->get_value_param("facebook_app_secret");
$table_user = $xeki_auth->getTableUser();
$logged_page = $xeki_auth->get_value_param("facebook_app_secret");
$login_page = $xeki_auth->get_value_param("facebook_app_secret");

$_DEFAULT_AUTH_LOGGED_PAGE=$xeki_auth->get_value_param("logged_page");

# login-callback.php
require_once(dirname(__FILE__) . "/../libs/vendor/facebook/graph-sdk/src/Facebook/autoload.php");
$fb = new Facebook\Facebook([
    'app_id' => "$facebook_app_id",
    'app_secret' => "$facebook_app_secret",
    'default_graph_version' => 'v2.10',
//'default_access_token' => '{access-token}', // optional
]);

$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
    $response = $fb->get('/me?fields=id,name,picture.type(large),first_name,last_name,email,age_range,birthday,gender,friends', $accessToken);

} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
//    \xeki\core::redirect($_DEFAULT_AUTH_LOGIN_PAGE);
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
//    \xeki\core::redirect($_DEFAULT_AUTH_LOGIN_PAGE);
    exit;
}

$xeki_auth = \xeki\module_manager::import_module('xeki_auth');

$user_array = $response->getDecodedBody();
// d($user_array);
// die();
$user_id = $user_array['id'];
$photo = $user_array['picture']['data']['url'];
$user_email = $user_array['email'];
$user_first_name = $user_array['first_name'];
$user_last_name = $user_array['last_name'];
$user_gender = $user_array['gender'];
$user_friends = $user_array['friends']['summary']['total_count'];
$extra_data = array(
    'idFacebook' => $user_id,
    'name' => $user_first_name,
    'photo' => $photo,
    'last_name' => $user_last_name,
    'gender' => $user_gender,
    "number_friends" =>$user_friends,
);
//d($extra_data);

$query = "SELECT * FROM $table_user WHERE idFacebook='$user_id'";
$res = $sql->query($query);
if (count($res) == 0) {
    ## check user register by email of facebook.
    $query = "SELECT * FROM $table_user WHERE email='$user_email'";
    $res = $sql->query($query);
    if (count($res) == 0) {
        ## user dont create

        $pass = incrementalHash();
        $res = $xeki_auth->secure_register($user_email, $pass, $extra_data);
//        $xeki_auth->login($user_email, $pass);
        \xeki\core::redirect($_DEFAULT_AUTH_LOGGED_PAGE);
    } else {
        $userByEmail = $res[0];
        $res = $sql->update($table_user, $extra_data, "email='$user_email'");

        $xeki_auth->login($user_email, $userByEmail['password'], true);
        \xeki\core::redirect($_DEFAULT_AUTH_LOGGED_PAGE);
    }
} else {
    $userById = $res[0];
    $extra_data = array(
        "number_friends" =>$user_friends,
    );
    $res = $sql->update($table_user, $extra_data, "email='$user_email'");

    ## user register by facebook
    $xeki_auth->login($userById['email'], $userById['password'], true);
    \xeki\core::redirect($_DEFAULT_AUTH_LOGGED_PAGE);
}

