<?php

/*
 *  Class:              Language
 *  Description:        Class used to describe and handle a Language.
 *                      It is used in every objects that uses translations and languages
 */
class Language
{
    private $lang_code;
    private $lang_name;
    private $lang_id;

    /*
     *  Method:             Language::__construct()
     *  Description:        Void constructor, used to allocate data.
     */
    public function __construct()
    {
    }

    /*
     *  Method:             Language::byCode( $conn, $lang_code )
     *  Description:        Static method used as constructor, to create a Language 
     *                      object by providing $conn (mysqli connection)
     *                      and $lang_code (two-letter code, to identify lang).
     */
    public static function byCode($conn, $lang_code)
    {
        $lang = new self();
        $result = $conn->query("SELECT * FROM language WHERE lang_code = \"$lang_code\"");

        if ($record = $result->fetch_assoc()) {
            $lang->loadByData($record['lang_id'], $record['lang_name'], $record['lang_code']);
            return $lang;
        }

        return false;
    }

    /*
     *  Method:             Language::byName( $conn, $lang_name)
     *  Description:        Static method used as constructor, to create a Language 
     *                      object by providing $conn (mysqli connection)
     *                      and $lang_name
     */
    public static function byName($conn, $lang_name)
    {
        $lang = new self();
        $result = $conn->query("SELECT * FROM language WHERE lang_name = \"$lang_name\"");

        if ($record = $result->fetch_assoc()) {
            $lang->loadByData($record['lang_id'], $record['lang_name'], $record['lang_code']);
            return $lang;
        }

        return false;
    }

        /*
     *  Method:             Language::byId( $conn, $lang_id)
     *  Description:        Static method used as constructor, to create a Language 
     *                      object by providing $conn (mysqli connection)
     *                      and $lang_id
     */
    public static function byId($conn, $lang_id)
    {
        $lang = new self();
        $result = $conn->query("SELECT * FROM language WHERE lang_id = $lang_id");

        if ($record = $result->fetch_assoc()) {
            $lang->loadByData($record['lang_id'], $record['lang_name'], $record['lang_code']);
            return $lang;
        }

        return false;
    }

    /*
     *  Method:             Language::byData( $lang_id, $lang_name, $lang_code )
     *  Description:        Static method used as constructor, to create a Language 
     *                      object by providing all data.
     */
    public static function byData($lang_id, $lang_name, $lang_code)
    {
        $lang = new self();
        $lang->loadByData($lang_id, $lang_name, $lang_code);
        return $lang;
    }

    public static function delete( $conn, $id ) {
        $sql_text = <<<EOD
    SELECT
        TABLE_NAME,
        COLUMN_NAME,
        CONSTRAINT_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM
        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE
        REFERENCED_TABLE_SCHEMA = ?
        AND REFERENCED_TABLE_NAME = 'language';
EOD;
        $stmt = $conn->prepare( $sql_text );
        $stmt->bind_param("s", DBUtils::readCredentials()['database'] );
        $stmt->execute();
        $stmt_result = $stmt->get_result();

        while( $record = $stmt_result->fetch_assoc() ) {

            $table_name = $record['TABLE_NAME'];
            $column_name = $record['COLUMN_NAME'];

            echo "$table_name -> $column_name    ";
            $sql_text = "SELECT COUNT(*) FROM $table_name WHERE $column_name = ?";
            $stmt_check = $conn->prepare( $sql_text );
            $stmt_check->bind_param("i", $id );
            $stmt_check->execute();

            $rows_count = $stmt_check->get_result()->fetch_assoc()['COUNT(*)'];
            echo "rows: $rows_count     ";
            if( $rows_count > 0 ) {
                $stmt_check->close();
                $stmt->close();
                return false;
            }

            $stmt_check->close();
        }
        $stmt->close();

        $sql_text = "DELETE FROM language WHERE lang_id = ?";
        $stmt = $conn->prepare( $sql_text );
        $stmt->bind_param("i", $id );
        $stmt->execute();
        $stmt->close();
        return true;
    }

    /*
     *  Method:             Language::loadByData( $lang_id, $lang_name, $lang_code )
     *  Description:        Load data to Language object
     */
    public function loadByData($lang_id, $lang_name, $lang_code)
    {
        $this->lang_id = $lang_id;
        $this->lang_name = $lang_name;
        $this->lang_code = $lang_code;
    }

    /*
     *  Method:             Language::getDefaultLanguage( $conn )
     *  Description:        By providing $conn (MySqli connection), it returns
     *                      the default Language object
     */
    public static function getDefaultLanguage($conn)
    {
        $lang = new self();
        $result = $conn->query("SELECT * FROM language lang INNER JOIN config conf ON conf.config_value = lang.lang_code WHERE conf.config_key = 'MAIN_LANGUAGE'");
        if ($record = $result->fetch_assoc()) {
            $lang->loadByData($record['lang_id'], $record['lang_name'], $record['lang_code']);
            return $lang;
        }
    }

    /*
     *  Method:             Language::loadByData( $lang_id, $lang_name, $lang_code )
     *  Description:        By providing $conn (MySqli connection), it returns all languages
     */
    public static function getAllLangueage($conn)
    {
        $langs = array();

        $result = $conn->query("SELECT * FROM language");
        while ($record = $result->fetch_assoc()) {
            $langs[] = Language::byData($record['lang_id'], $record['lang_name'], $record['lang_code']);
        }

        return $langs;
    }

    public static function addNew($conn, $lang_name, $lang_code)
    {
        $stmt = $conn->prepare("INSERT INTO language (lang_name, lang_code) VALUES ( ?, ? )");
        $stmt->bind_param("ss", $lang_name, $lang_code);
        $stmt->execute();
        $stmt->close();
    }

    public function update($conn)
    {
        $stmt = $conn->prepare("UPDATE language SET lang_name = ?, lang_code = ? WHERE lang_id = ?");
        $stmt->bind_param("ssi", $this->lang_name, $this->lang_code, $this->lang_id );
        $stmt->execute();
        $stmt->close();
    }


    /*      GETTER - SETTER methods     */

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function getLangName()
    {
        return $this->lang_name;
    }

    public function getLangCode()
    {
        return $this->lang_code;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function setLangName($lang_name)
    {
        $this->lang_name = $lang_name;
    }

    public function setLangCode($lang_code)
    {
        $this->lang_code = $lang_code;
    }
}
