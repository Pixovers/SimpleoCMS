<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/language.php";

/*
 *  Class:              TranslatableObject
 *  Description:        Template class to handle translations in obects like Post or Category
 */
class TranslatableObject {

    //Language object
    protected $lang;
    protected $id;
    protected $default_lang_ref_id;

    /*
     *  Method:             TranslatableObject::__construct()
     *  Description:        Constructor
     */
    public function __construct( $lang, $id, $default_lang_ref_id )
    {
        $this->lang = $lang;
        $this->id = $id;
        $this->default_lang_ref_id = $default_lang_ref_id;
    }

    /*
     *      Void methods used in inherited classes, to select objects
     *      by lang, default lang, and so on.
     */
    public function fetchByLang( $conn, $lang ) {

    }

    public function fetchByDefaultLang( $conn ) {

    }

    public static function fetchAll( $conn ) {

    }

    public static function fetchAllByLang( $conn, $lang) {
        
    }

    public static function fetchAllByDefaultLang( $conn ) {
        
    }

    /*      GETTER - SETTER methods     */

    public function getLang() {
        return $this->lang;
    }

    public function setLang( $lang ) {
        $this->lang = $lang;
    }

    public function getId() {
        return $this->id;
    }

    public function setId( $id ) {
        $this->id = $id;
    }

    public function getDefaultLangRefId() {
        return $this->default_lang_ref_id;
    }

    public function setDefaultLangRefId( $default_lang_ref_id ) {
        $this->default_lang_ref_id = $default_lang_ref_id;
    }
}