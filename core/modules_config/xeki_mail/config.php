<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        "type_sender" => "mailgun", // smtp,local,mailgun

        // mailgun ::config
        "mailgun_key"=>'',
        "mailgun_domain"=>'wepet.io',

        // smtp config
        "smtp_domain" =>"smtp.gmail.com",
        "smtp_email" =>"jossedaviid1@gmail.com",
        "smtp_pass" =>"29Hasa29-",
        
        "smtp_port" =>"587",
        "smtp_secure" =>"tls",

        "default_from" => "WePet <contacto@wepet.io>",

        #aws
        'aws_key'    => 'key',
        'aws_secret' => 'secret-key',
        'aws_region' => 'us-west-2' //http://docs.aws.amazon.com/general/latest/gr/rande.html

    ),

);