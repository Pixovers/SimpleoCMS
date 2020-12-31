<?php
session_start();


include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/lang_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/db_utils.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$_URI = parse_url($_SERVER['REQUEST_URI']);
echo var_dump($_URI);

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/../config/db_credentials.json")) {
    include_once "../admin/setup.php";
} else {
    $_CONN = DBUtils::createConnection();
    list($lang_id, $uri) = LangUtils::getCurrentLanguage($_URI['path'], DBUtils::createConnection());
    switch ($uri) {
        case "/":
            include_once "../pages/home/home.php";
            exit();

        case "/admin":
        case "/admin/":
            include_once "../admin/login.php";
            exit();

        case "/admin/items":
            include_once "../admin/items.php";
            exit();

        case "/admin/new_post":
            include_once "../admin/new_post.php";
            exit();

        case "/admin/new-category":
            include_once "../admin/new-category.php";
            exit();

        case "/admin/new-language":
            include_once "../admin/new-language.php";
            exit();
    }
}
