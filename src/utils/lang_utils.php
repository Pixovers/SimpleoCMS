<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/db_utils.php";

class LangUtils {
    public static function getCurrentLanguage( $path,  $conn ) {

        $languages = $conn->query( "SELECT * FROM language" )->fetch_all(MYSQLI_ASSOC);
        $default_lang = $conn->query( "SELECT * FROM language lang INNER JOIN config conf ON conf.config_value = lang.lang_code WHERE conf.config_key = 'MAIN_LANGUAGE'")->fetch_assoc();
        $url_lang_field = explode( "/", $path )[1];

        foreach( $languages as $lang ) {
            if( $lang['lang_code'] == $url_lang_field ) {
                
                return array( $lang['lang_id'], substr( $path, 3 ) );
            }
        }
        
        return array( $default_lang['lang_id'], $path );
    }
}