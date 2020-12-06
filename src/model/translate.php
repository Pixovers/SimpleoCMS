<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/translatable_object.php";


/*
 *  Class:              Translate
 *  Description:        Class used to create, delete and manage Translates
 */
class Translate extends TranslatableObject {

    private $value;

    public function __construct( $value, $lang, $id, $default_lang_ref_id )
    {
        parent::__construct( $lang, $id, $default_lang_ref_id );

        $this->value = $value;
    }

    public function fetchByLang( $conn, $lang ) {
        $sql_text = "SELECT * FROM translates t INNER JOIN language lang ON lang.lang_id = t.translate_lang_id WHERE lang.lang_id = " . $lang->getLangId() . " AND t.translate_lang_ref = " . $this->default_lang_ref_id . " LIMIT 1";
        $result = $conn->query( $sql_text );

        if( $record = $result->fetch_assoc() ) {
            return new self(    $record['cat_value'],
                                Language::byData(   $record['lang_id'], 
                                                    $record['lang_name'], 
                                                    $record['lang_code'] ),
                                $record['translate_id'],
                                $record['translate_lang_ref'] );
        }
    }

    public function fetchByDefaultLang( $conn ) {
        return $this->fetchByLang( $conn, Language::getDefaultLanguage( $conn ) );
    }

    public static function fetchAll( $conn ) {
        $sql_text = "SELECT * FROM translates t INNER JOIN language lang ON lang.lang_id = t.translate_lang_id";
        $result = $conn->query( $sql_text );

        $categories = array();

        while( $record = $result->fetch_assoc() ) {
            $categories[] = new self(   $record['cat_value'],
                                        Language::byData(   $record['lang_id'], 
                                                            $record['lang_name'], 
                                                            $record['lang_code'] ),
                                        $record['translate_id'],
                                        $record['translate_lang_ref'] );
        }

        return $categories;
    }

    public static function fetchAllByLang( $conn, $lang) {
        $sql_text = "SELECT * FROM translate t INNER JOIN language lang ON lang.lang_id = t.translate_lang_id WHERE lang.lang_id = " . $lang->getLangId();
        $result = $conn->query( $sql_text );

        $categories = array();

        while( $record = $result->fetch_assoc() ) {
            $categories[] = new self(   $record['cat_value'],
                                        Language::byData(   $record['lang_id'], 
                                                            $record['lang_name'], 
                                                            $record['lang_code'] ),
                                        $record['translate_id'],
                                        $record['translate_lang_ref'] );
        }

        return $categories;       
    }

    public static function addNew(  $conn,
                                    $value,
                                    $lang,
                                    $default_lang_ref_id ) 
    {
    $langId = $lang->getLangId();

        if( is_null( $default_lang_ref_id ) ) {
            $sql_text = <<<EOD
INSERT INTO category (translate_value,translate_lang_id,translate_lang_ref)
VALUES ("$value",$langId,NULL);
SELECT LAST_INSERT_ID();
EOD;
            $conn->multi_query($sql_text);

            //fisrt query result
            $conn->store_result();

            //second query result: id of last record
            $conn->next_result();
            $id = $conn->store_result()->fetch_assoc()['LAST_INSERT_ID()'];
            $conn->query("UPDATE translates set translate_lang_ref=$id WHERE translate_id=$id");
        } else {
            $sql_text = <<<EOD
INSERT INTO category (translate_value,translate_lang_id,translate_lang_ref)
VALUES ("$value",$langId,$default_lang_ref_id);
EOD;
            if( $conn->query($sql_text) == false ) {
                return false;
            }
        }   
                
    }

    public static function fetchAllByDefaultLang( $conn ) {
        return Category::fetchAllByLang( $conn, Language::getDefaultLanguage( $conn ) );
    }


    /*      GETTERa - SETTER methods     */

    public function getValue() {
        return $this->value;
    }

    public function setValue( $value ) {
        $this->value = $value;
    }

}