<?php

class Utils {
    public static function error_404() {
        include_once( $_SERVER['DOCUMENT_ROOT'] . "/pages/error_404.php");
        exit();
    }
}