# Sitemap Guide

This module auto generated the sitemap of site with include this module, and complete the params.

+ Config
```php


$MODULE_DATA_CONFIG = array(
    "main" => array(
        "domain" => "http://domain.co/",
        "urls" =>array(
            "nosotros", // static pages


            // complex
            array(
                "table"=>"blog_article", // table sql
                "base"=>"article", // base url// domain/base/{{slug}}
                "slug"=>"slug", // {{slug}}
                "priority"=>"0.5", //
                "changefreq"=>"monthly",//always,hourly,daily,weekly,monthly,yearly,never
            ),
        ),


    ),
//    "secondary" => array(
//    ),
);

```

+ Add Popup

```php
$popUp->add_msg("Title","message");
```
+ Common errors

Die(); on not_controllers pages or other files
Change die to returnl
