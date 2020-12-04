<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/language.php";

/*
 *  Class:              TranslatableObject
 *  Description:        Template class to handle translations in obects like Post or Category
 */
class TranslatableObject {

    //Language object
    private $lang;

    /*
     *  Method:             TranslatableObject::__construct()
     *  Description:        Constructor
     */
    public function __construct( $lang )
    {
        $this->lang = $lang;
    }

    /*
     *  Method:             TranslatableObject::__construct()
     *  Description:        Constructor
     */
    public static function fetchByLang( $conn, $lang ) {

    }

    /*
     *      Void methods used in inherited classes, to select objects
     *      by lang, default lang, and so on.
     */

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


}