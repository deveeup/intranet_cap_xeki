<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        //default configuration pages
//        "default_pages" => true,

        //custom configuration pages
        "default_pages" => false,
        "folder_base" => "core/pages",
        "folder_pages" => "/module_auth",


        //custom configuration email // for cusmon uncomment this 
        // "default_emails" => false,
        // "default_email_base" => "core/pages",

        //default configuration

        // config urls
        "use_module_controllers" => true, // for custom urls set false
        "login_page_url" => "login",
        "register_page_url" => "registro",
        "logout_page_url" => "logout",
        "recover_pass_page_url" => "recover-pass",


        // db_info
        "table_user" => "user",
        "field_id" => "id",
        "field_user" => "email",
        "field_password" => "password",
        "field_recover_code" => "recover_code",
        "table_user_temp" => "customer_temp",
        "temp_field_id" => "id",
        "logged_page" => "mi-cuenta",

        // config facebook
        "facebook_login" => false,  # for activate facebook login
        "app_id" => false,
        "app_secret" => false,
        "facebook_auth_page" => "auth_facebook",
        "facebook_call_back_url" => "auth_facebook_callback",

        "encryption_method" => "sha256",
        "ultra_secure" => true,
    ),
//    "secondary" => array(
//    ),
);