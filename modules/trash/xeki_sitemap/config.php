<?php

$MODULE_DATA_CONFIG = array(
    "main" => array(
        "domain" => "http://domain.co/",
        "urls" => array(
            "nosotros", // static pages
            // complex
            array(
                "table" => "blog_article", // table sql
                "base" => "article", // base url// domain/base/{{slug}}
                "slug" => "slug", // {{slug}}
                "priority" => "0.5", //
                "changefreq" => "monthly",//always,hourly,daily,weekly,monthly,yearly,never
            ),

        ),
    ),
//    "secondary" => array(
//    ),
);