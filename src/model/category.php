<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/meta_object.php";


/*
 *  Class:              Category
 *  Description:        Class used to create, delete and manage Categories
 */
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

    public static function addNew(  $conn,
                                    $name, 
                                    $url,
                                    $lang,
                                    $default_lang_ref_id,
                                    $meta_title,
                                    $meta_description ) 
    {
    $langId = $lang->getLangId();

        if( is_null( $default_lang_ref_id ) ) {
            $sql_text = <<<EOD
INSERT INTO category (cat_name,cat_url,cat_lang_id,cat_lang_ref,cat_meta_title,cat_meta_description)
VALUES ("$name","$url",$langId,NULL,"$meta_title","$meta_description");
SELECT LAST_INSERT_ID();
EOD;
            $conn->multi_query($sql_text);

            //fisrt query result
            $conn->store_result();

            //second query result: id of last record
            $conn->next_result();
            $id = $conn->store_result()->fetch_assoc()['LAST_INSERT_ID()'];
            $conn->query("UPDATE category set cat_lang_ref=$id WHERE category_id=$id");
        } else {
            $sql_text = <<<EOD
INSERT INTO category (cat_name,cat_url,cat_lang_id,cat_lang_ref,cat_meta_title,cat_meta_description)
VALUES ("$name","$url",$langId,$default_lang_ref_id,"$meta_title","$meta_description");
EOD;
            if( $conn->query($sql_text) == false ) {
                return false;
            }
        }   
                
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