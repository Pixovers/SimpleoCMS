<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/translatable_object.php";


/*
 *  Class:              MetaObject
 *  Description:        Class to manage meta tags in objects like posts or categories
 */
class MetaObject extends TranslatableObject {

    //meta title text
    protected $meta_title;

    //meta description text
    protected $meta_description;

    /*
     *  Method:             MetaObject::__construct()
     *  Description:        Create object by providing a $language (Language), and two strings:
     *                      $meta_title and $meta_description 
     */
    public function __construct( $language,  $id, $default_lang_ref_id, $meta_title, $meta_description )
    {
        parent::__construct( $language, $id, $default_lang_ref_id );

        $this->meta_description = $meta_description;
        $this->meta_title = $meta_title;
    }

    /*      GETTER - SETTER methods     */

    public function getMetaTitle() {
        return $this->meta_title;
    }

    public function getMetaDescription() {
        return $this->meta_description;
    }

    public function setMetaTitle( $meta_title ) {
        $this->meta_title = $meta_title;
    }

    public function setMetaDescription( $meta_description ) {
        $this->meta_description = $meta_description;
    }

}