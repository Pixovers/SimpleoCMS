<?php 

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/lang_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/db_utils.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo var_dump(parse_url($_SERVER['REQUEST_URI']));

$_URI = parse_url($_SERVER['REQUEST_URI']);

if( !file_exists( $_SERVER['DOCUMENT_ROOT'] . "/../config/db_credentials.json") ) {
    include_once "../admin/setup.php";
} else {
    
    list($lang_id, $uri) = LangUtils::getCurrentLanguage( $_URI['path'], DBUtils::createConnection() );
    switch( $uri ) {
        case "/":
            include_once "../pages/home/home.php";
            exit();

        case "/admin":
            include_once "../admin/login.php";
            exit();

        case "/admin/items":
            include_once "../admin/items.php";
            exit();
        
    }

    

}  
