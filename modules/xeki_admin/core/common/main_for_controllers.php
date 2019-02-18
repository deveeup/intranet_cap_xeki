<?php
# main 

// load module
$xeki_admin_name="xeki_admin";
$xeki_admin = \xeki\module_manager::import_module("$xeki_admin_name");

// load variables necesaries of module
$name_space = $xeki_admin->get_value_param("name_space");
$base_url   = $xeki_admin->get_value_param("base_url");
$base_url   = \xeki\core::$URL_BASE_COMPLETE.$base_url;


//d($base_url);
//d($name_space);
// load module auth necesary for xeki_admin
$xeki_auth = \xeki\module_manager::import_module('xeki_auth');
$xeki_auth->set_logged_page($base_url);
$xeki_auth->set_name_space($name_space);


// example of list items_menu

$menu_list = <<< JSON
{ 
    "items_menu":[
        {
            "type"  : "url",
            "icon"  : "fa fa-th-large",
            "text"  : "Blog",
            "url"   : "blog/",
            "background"  : "#ff9933"
        }
        {
            "type"  : "url",
            "icon"  : "fa fa-link",
            "text"  : "another demo url",            
            "url"   : "ini_url/next/url/next",
            "background"  : "#ff33ff"
        },
        {
            "type"  : "title",
            "title" : "User Manager",
            "class" : "space15"
        },
        {
            "type"  : "url",
            "icon"  : "fa fa-user",
            "text"  : "User Manager",            
            "url"   : "users-admin/",
            "background"  : "#66ccff"
        },
        
        {
            "type"  : "div",
            "class" : "space15"
        },
        {
            "type"  : "group",
            "icon"  : "fa fa-link",
            "text"  : "Users Admin",
            "items" : [
                { 
                   "url"  : "users-admin/",
                   "text" : "Main"
                },
                { 
                   "url"  : "users-admin/create",
                   "text" : "Create"
                },
                { 
                   "url"  : "users-admin/edit",
                   "text" : "Edit"
                }
            ]
        }
    ]
}
JSON;

$menu_list = <<< JSON
{ 
    "items_menu":[
        {
            "type"  : "url",
            "icon"  : "fa fa-th-large",
            "text"  : "Blog",
            "url"   : "blog/",
            "background"  : "#ff9933"
        }
    ]
}
JSON;


// read
$menu_list = $xeki_admin->get_menu_list();


// TODO this to singleton
\xeki\html_manager::add_extra_data("menu_list",$menu_list);

\xeki\html_manager::add_extra_data("xeki_admin_url_base","{$base_url}"); // this info work aaas {{somedata}} in html pages

\xeki\html_manager::add_extra_data("xeki_admin_title","xeki Admin");


$xeki_auth = \xeki\module_manager::import_module('xeki_auth');



