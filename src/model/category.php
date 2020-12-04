<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/meta_object.php";

class Category extends MetaObject {

    private $id;
    private $name;
    private $url;
    private $default_lang_ref;

}