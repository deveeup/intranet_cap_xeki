<?php
require_once dirname(__FILE__) . "/../common/main_for_controllers.php";
## controllers inicializa

// check is is logged
// $user_zone->pageLoginCheck();


// if is loged show main_panel

//d($AG_MODULES);

$xeki_auth = \xeki\module_manager::import_module('xeki_auth');
$xeki_auth->check_login();


// create list menu

$menu_list = <<< JSON
{ 
    "items_menu":[
        {
            "type": "url",
            "icon": "fa fa-link",
            "text": "demo-url",
            "url" : "ini_url/next/url/next"
        },
        {
            "type": "div",
            "class": "space15"
        },
        {
            "type": "url",
            "icon": "fa fa-link",
            "text": "demo-url",
            "url" : "ini_url/next/url/next"
        },
        {
            "type": "group",
            "icon": "fa fa-link",
            "text": "Users Admin",
            "items" : [
                { 
                   "url" :"users-admin/",
                   "text":"Main"
                },
                { 
                   "url" :"users-admin/create",
                   "text":"Create"
                },
                { 
                   "url" :"users-admin/edit",
                   "text":"Edit"
                }
            ]
        }
    ]
}
JSON;

$menu_list = json_decode($menu_list, true);

$menu_list = $menu_list ['items_menu'];

$data_to_print= array();
$data_to_print['menu_list']=$menu_list;
\xeki\html_manager::render('main_panel.html', $data_to_print);