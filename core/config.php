<?php
/**
 * Agent config for default variables
 *
 *
 */


$_BASE_PATH = '/';
$AG_FORCE_SSL = false;

//System vars
$_DEFAULT_FATAL_PAGE_ERROR = '_default_error.php';

#for develop escenar for display errors
$_DEBUG_MODE = true;
$_DEFAULT_TITLE = '';
$_DEFAULT_END_TITLE = ' - lafeintranet.com';
$_DEFAULT_DESCRIPTION = '';
$_DEFAULT_END_DESCRIPTION = '';
$_DEFAULT_KEYWORDS = 'Key,word';
$_DEFAULT_END_KEYWORDS = ', key,word';
$_DEFAULT_PAGE_ERROR = '_default_error.php';


$AG_FORCE_SSL=true;
$AG_SSL_DOMAINS=array( ## keep emply for all domains
    'example.com',
);

// this vars are avalible on render and any part of controllers
$GLOBAL_VARS = array(
    "global_variable"=>"##XXYY33",
);

// $_ARRAY_MODULES_TO_LOAD_URLS = false; # false for no load 
$_ARRAY_MODULES_TO_LOAD_URLS = array(); # array emply for all load
$_ARRAY_MODULES_TO_LOAD_URLS = array(# array No emply for load only the modules
    "xeki_admin",
    "xeki_auth",
    // "ag_ev2",
    // "ag_catalog",
     "xeki_sitemap",
);   

// for run star
// for run end
$_RUN_START_MODULES = false;
$_ARRAY_RUN_START = array(
    #modules_names
    // 'ag_auth'
);

$RUN_END_MODULES = false;

$_ARRAY_RUN_END = array(
    #modules_names
    'xeki_popup'
);