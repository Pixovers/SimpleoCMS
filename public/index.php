<?php 

session_start();

if( !file_exists( $_SERVER['DOCUMENT_ROOT'] . "/../config/db_credentials.json") ) {
    include_once "../pages/setup.php";
} else {
    echo "sito già creato";
}
