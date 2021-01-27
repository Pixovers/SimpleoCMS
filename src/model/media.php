<?php


class Media {

    private $id;
    private $url_slug;
    private $title;
    private $description;
    private $alt;

    public function __construct( $id, $url_slug, $title, $description, $alt ) {
        
        $this->id = $id;
        $this->url_slug = $url_slug;
        $this->title = $title;
        $this->description = $description;
        $this->alt = $alt;
        //lui e' 
    }

    


    public static function addNew( $conn, $url_slug, $title, $description, $alt ) {
        
        $stmt = $conn->prepare("INSERT INTO media VALUES ('',?,?,?,?)");
        $stmt->bind_param("ssss",$url_slug, $title, $description, $alt);
        $stmt->execute();
        $stmt->close();
    }

    //getters - setters

    public function getId() {
        return $this->id;
    }
    
    public function setId( $id ) {
        $this->id = $id;
    }

    public function getUrlSlug() {
        return $this->url_slug;
    }

    public function setUrlSlug( $url_slug ) {
        $this->url_slug = $url_slug;
    }

    public function getTitle() {
        return $this->$title;
    }

    public function setTitle ($title){
        $this->$title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription( $description ) {
        $this->$description = $description;
    }
    

    public function setAlt ($alt){
        $this->$alt = $alt;
    }

    public function getAlt() {
        return $alt->alt;
    }

    
}


