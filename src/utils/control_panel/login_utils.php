<?php



class LoginUtils {
    public static function isValidEmail( $email_str) {
        return filter_var( $email_str, FILTER_VALIDATE_EMAIL );
    }

}

