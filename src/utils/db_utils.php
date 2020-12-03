<?php

class DBUtils {
    public static function CheckConnection( $host, $username, $pwd ) {
        $conn = new mysqli( $host, $username, $pwd );

        if( $conn->connect_errno ) {
            return false;
        }

        $conn->close();
        return true;
    }

    public static function saveCredentials( $host, $username, $password, $database ) {
        $credentials = array(   "host" => $host,
                                "username" => $username,
                                "password" => $password,
                                "database" => $database );
        
        $credentials_file = fopen( $_SERVER['DOCUMENT_ROOT'] . "/../config/db_credentials.json", "w" );

        if( $credentials_file !== false ) {
            fwrite( $credentials_file, json_encode( $credentials ) );
            fclose( $credentials_file );
            return true;
        } else {
            return false;
        }
    }

    public static function readCredentials() {
        $credentials_file = fopen( $_SERVER['DOCUMENT_ROOT'] . "/../config/db_credentials.json", "r" );

        if( $credentials_file !== false ) {
            $data = fread( $credentials_file, filesize( $_SERVER['DOCUMENT_ROOT'] . "/../config/db_credentials.json" ) );
            fclose( $credentials_file );
            return json_decode( $data, true );
        } else {
            return false;
        }
    }

    public static function createConnection( $select_db = true ) {
        if( $credentials = DBUtils::readCredentials() ) {
        
            if( !$select_db ) {
                $conn = new mysqli( $credentials['host'], 
                                    $credentials['username'], 
                                    $credentials['password'] );
            } else {
                $conn = new mysqli( $credentials['host'], 
                                    $credentials['username'], 
                                    $credentials['password'],
                                    $credentials['database'] );
            }

            if( $conn->connect_errno ) {
                return false;
            }

            return $conn;
        }

        return false;
    }
}