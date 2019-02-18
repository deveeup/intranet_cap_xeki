<?php
$MODULE_DATA_CONFIG = array(
    "main" => array(
        //default configuration pages
//        "default_pages" => true,

        // for cloudflare clean cache
        "cloudflare_email"=>"",
        "cloudflare_api_key"=>"",
        "cloudflare_domain"=>"", # if is empty clean ALL domains of user

        //custom configuration pages
        "default_pages" => true,
        "folder_base" => "core/pages",
        "folder_pages" => "/module_admin",

        // aws config
        "aws_upload" => false,
        "aws_bucket" => "cdn-bucket-com",
        "aws_folder" => "img/uploads/",
        "aws_key_id" => "AWSID",
        "aws_access_key" => "aWsId",
        "aws_region" => "eu-west-1",
        //
        // "ap-northeast-1",
        // "ap-southeast-2",
        // "ap-southeast-1",
        // "cn-north-1",
        // "eu-central-1",
        // "eu-west-1",
        // "us-east-1",
        // "us-west-1",
        // "us-west-2",
        // "sa-east-1",




        //custom configuration email // for cusmon uncomment this
        // "default_emails" => false,
        // "default_email_base" => "core/pages",

        // Name space for auth
        "name_space"=>"xeki_backend",

        // config urls
        "use_module_controllers"=>true, // for custom urls set false
        "base_url" => "xeki_admin",

        //
        "encryption_method" => "sha256",
        "ultra_secure" => true,

        // array of list of names of modules for
        "modules_to_load" => array(
            "xeki_catalog",
            "blog",
            "pages",
            "xeki_form",

//          "blog",
//          "catalog",
            "xeki_admin",
        ),
    ),
);