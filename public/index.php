<?php
session_start();


include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/lang_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/db_utils.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$_URI = parse_url($_SERVER['REQUEST_URI']);

//echo var_dump($_URI);

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

        case "/admin/posts":
        case "/admin/posts/":
            include_once "../admin/posts.php";
            exit();

        case "/admin/posts/new":
        case "/admin/posts/new/":
            include_once "../admin/new_post.php";
            exit();

        case "/admin/posts/edit":
        case "/admin/posts/edit/":
            include_once "../admin/edit-post.php";
            exit();

        case "/admin/categories/new":
        case "/admin/categories/new/":
            include_once "../admin/new-category.php";
            exit();

        case "/admin/categories":
        case "/admin/categories":
            include_once "../admin/categories.php";
            exit();

        case "/admin/categories/edit":
        case "/admin/categories/edit/":
            include_once "../admin/edit-category.php";
            exit();

        case "/admin/languages/new":
        case "/admin/languages/new/":
            include_once "../admin/new-language.php";
            exit();

        case "/admin/languages":
        case "/admin/languages/":
            include_once "../admin/languages.php";
            exit();

        case "/admin/languages/edit":
        case "/admin/languages/edit/":
            include_once "../admin/edit-language.php";
            exit();

        case "/admin/languages/delete":
        case "/admin/languages/delete/":
            include_once "../admin/languages.php";
            exit();
    }
}
