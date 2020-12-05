<?php 

session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/lang_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/db_utils.php";
 
list($lang_id, $uri) = LangUtils::getCurrentLanguage( DBUtils::createConnection() );
switch( $uri ) {
    case "/admin":
        include_once "../admin/login.php";
        exit();
        

}

if( file_exists( $_SERVER['DOCUMENT_ROOT'] . "/../config/db_credentials.json") ) {
    include_once "../pages/control_panel/setup.php";
} else {
    
    //echo "sito già creato";
    include_once "../admin/login.php";
}  
