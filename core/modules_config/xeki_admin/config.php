<?php
$MODULE_DATA_CONFIG = array(
    "main" => array(
        //default configuration pages
//        "default_pages" => true,



        //custom configuration pages
        "default_pages" => true,
        "folder_base" => "core/pages",
        "folder_pages" => "/module_user_zone",


        //custom configuration email // for cusmon uncomment this
        // "default_emails" => false,
        // "default_email_base" => "core/pages",

        // Name space for auth
        "name_space"=>"ag_backend",

        // config urls
        "use_module_controllers"=>true, // for custom urls set false
        "base_url" => "backoffice",

        //
        "encryption_method" => "sha256",
        "ultra_secure" => true,

        // array of list of names of modules for
        "modules_to_load" => array(
//          "catalog",

            // "blog",
            // "pages",
            // "ag_admin",
        ),
    ),
);