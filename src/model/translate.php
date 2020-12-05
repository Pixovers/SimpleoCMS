<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/translatable_object.php";

class Translate extends TranslatableObject {

    private $value;

    public function __construct( $value, $lang, $id, $default_lang_ref_id )
    {
        parent::__construct( $lang, $id, $default_lang_ref_id );

        $this->value = $value;
    }

    /*      GETTER - SETTER methods     */

    public function getValue() {
        return $this->value;
    }

    public function setValue( $value ) {
        $this->value = $value;
    }

}