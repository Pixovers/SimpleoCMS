<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/meta_object.php";

class Category extends MetaObject {

    private $name;
    private $url;

    /*
     *  Method:             Category::__construct()
     *  Description:        Create object by providing all informations about a category
     */
    public function __construct(    $name,                      //category name
                                    $url,                       //category url field
                                    $lang,                      //Language object 
                                    $id,                        //category id
                                    $default_lang_ref_id,       //id of the main translation of category
                                    $meta_title,                //meta title string
                                    $meta_description )         //meta description string
    {
        parent::__construct( $lang, $id, $default_lang_ref_id, $meta_title, $meta_description );
        
        $this->name = $name;
        $this->url = $url;
    }

    public function fetchByLang( $conn, $lang ) {
        $sql_text = "SELECT * FROM category cat INNER JOIN language lang ON lang.lang_id = cat.cat_lang_id WHERE lang.lang_id = " . $lang->getLangId() . " AND cat.cat_lang_ref = " . $this->default_lang_ref_id . " LIMIT 1";
        $result = $conn->query( $sql_text );

        if( $record = $result->fetch_assoc() ) {
            return new self(    $record['cat_name'],
                                $record['cat_url'],
                                Language::byData( $record['lang_id'], 
                                                $record['lang_name'], 
                                                $record['lang_code'] ),
                                $record['category_id'],
                                $record['cat_lang_ref'],
                                $record['cat_meta_title'],
                                $record['cat_meta_description'] );
        }
    }

    public function fetchByDefaultLang( $conn ) {
        return $this->fetchByLang( $conn, Language::getDefaultLanguage( $conn ) );
    }

    public static function fetchAll( $conn ) {
        $sql_text = "SELECT * FROM category cat INNER JOIN language lang ON lang.lang_id = cat.cat_lang_id";
        $result = $conn->query( $sql_text );

        $categories = array();

        while( $record = $result->fetch_assoc() ) {
            $categories[] = new self( $record['cat_name'],
                                      $record['cat_url'],
                                      Language::byData( $record['lang_id'], 
                                                        $record['lang_name'], 
                                                        $record['lang_code'] ),
                                      $record['category_id'],
                                      $record['cat_lang_ref'], 
                                      $record['cat_meta_title'],
                                      $record['cat_meta_description']);
        }

        return $categories;
    }

    public static function fetchAllByLang( $conn, $lang) {
        $sql_text = "SELECT * FROM category cat INNER JOIN language lang ON lang.lang_id = cat.cat_lang_id WHERE lang.lang_id = " . $lang->getLangId();
        $result = $conn->query( $sql_text );

        $categories = array();

        while( $record = $result->fetch_assoc() ) {
            $categories[] = new self( $record['cat_name'],
                                      $record['cat_url'],
                                      Language::byData( $record['lang_id'], 
                                                        $record['lang_name'], 
                                                        $record['lang_code'] ),
                                      $record['category_id'],
                                      $record['cat_lang_ref'],
                                      $record['cat_meta_title'],
                                      $record['cat_meta_description']);
        }

        return $categories;       
    }

    public static function addNew(  $name, 
                                    $url,
                                    $lang,
                                    $default_lang_ref_id,
                                    $meta_title,
                                    $meta_description ) 
    {
    $sql_text = "INSERT INTO category (cat_name,cat_url,cat_lang_id,cat_lang_ref,cat_meta_title,cat_meta_description) " . 
                "VALUES (\"$name\",\"$url\",".$lang->getLangId().",$default_lang_ref_id,\"$meta_title\",\"$meta_description\")";

                /*
                INSERT INTO language (lang_name,lang_code) VALUES ("ciaod","i");
SELECT LAST_INSERT_ID();
*/
    }

    public static function fetchAllByDefaultLang( $conn ) {
        return Category::fetchAllByLang( $conn, Language::getDefaultLanguage( $conn ) );
    }


    /*      GETTER - SETTER methods     */

    public function getName() {
        return $this->name;
    }

    public function setName( $name ) {
        $this->name = $name;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl( $url ) {
        $this->url = $url;
    }

    


}