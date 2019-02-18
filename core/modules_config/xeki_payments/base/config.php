<?php
/**
 * Agent config for default variables
 *
 *
 */

#for develop escenar for display errors
$_DEBUG_MODE = true;

$_DEFAULT_TITLE = 'Funeraria Capillas de la fe';

$_DEFAULT_END_TITLE = ' - Funeraria Capillas de la fe';

$_DEFAULT_DESCRIPTION = 'Funeraria Capillas de la fe, somos un grupo de personas que saben lo difícil que es la partida de un ser querido.';

$_DEFAULT_END_DESCRIPTION = ' - Funeraria Capillas de la fe';

$_DEFAULT_KEYWORDS = 'Bogotá,Colombia,Capillas de la fe, Funeraria,Prevision,Exequial';

$_DEFAULT_END_KEYWORDS = ', Capillas de la fe';

$_DEFAULT_AUTH_LOGIN_PAGE = 'login'; #to no log page por basic autentication
$_DEFAULT_AUTH_LOGIN_2_PAGE = 'verify'; #to no log security page por advance autentication
$_DEFAULT_AUTH_LOGGED_PAGE = 'home'; #main loged page

$_DEFAULT_PAGE_ERROR = '_error.php';


#--------------
#  ACCOUNT CONFIG
#--------------
$_DEFAULT_GOOGLE_ANALYTIC = 'UA-35160340-16';


$_DEFAULT_PAY_U_API_KEY = '0HAv5UW5F1nhJQL2B6mlw8UaW9';
$_DEFAULT_PAY_U_MERCHANT_ID = '544699';
$_DEFAULT_PAY_U_API_LOGIN = '1DHjhD5tnuZ7p58';
$_DEFAULT_PAY_U_PUBLIC_KEY = 'PK64g5ZJ53rk0F0tf3C806E9TA';

//$_DEFAULT_PAY_U_API_KEY='169evaeja9sd82pddllsemi032';
//$_DEFAULT_PAY_U_MERCHANT_ID='511213';
//$_DEFAULT_PAY_U_API_LOGIN='31265642ebb95aa';
//$_DEFAULT_PAY_U_PUBLIC_KEY='PKgbb6j488duz7y86C9Z96xDEg';


#--------------
#  AUTH CONFIG
#--------------
$_DEFAULT_AUTH_ENCRYPTION_METHOD = 'sha256';
#db info
$_DEFAULT_AUTH_TABLE_USER = 'customer';
$_DEFAULT_AUTH_FIELD_ID = 'id';
$_DEFAULT_AUTH_FIELD_USER = 'email';
$_DEFAULT_AUTH_FIELD_PASSWORD = 'password';
$_DEFAULT_AUTH_FIELD_RECOVER_CODE = 'recover';

#db info user_temp
$_DEFAULT_AUTH_TABLE_USER_TEMP = 'customer_temp';
$_DEFAULT_AUTH_FIELD_ID_TEMP = 'id';

$_DEFAULT_AUTH_LOGIN_PAGE = 'login'; #to no log page por basic autentication
$_DEFAULT_AUTH_LOGIN_2_PAGE = 'login'; #to no log security page por advance autentication
$_DEFAULT_AUTH_LOGGED_PAGE = 'pedidos'; #main loged page

$_DEFAULT_PAGE_ERROR = '_error.php';