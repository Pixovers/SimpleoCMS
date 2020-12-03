<?php

include $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/control_panel/login_utils.php";


if( isset( $_GET['error'] ) ) {
    switch( $_GET['error'] ) {
        case "empty":
            $error_message = "Empty fields!";
            break;
        case "invalid_email":
            $error_message = "Invalid email!";
            break;
        case "pwdmatch":
            $error_message = "Passwords don't match!";
            break;
        case "pwd":
            $error_message = "Password must be at least 8 characters long, and must have at least one number!";
             break;
        default:
            $error_message = "Unknown Error";
    }
    echo $error_message;
} else if( isset( $_GET['submit'] ) ) {
    
    if( empty( $_GET['email']) ||
        empty( $_GET['pwd']) ||
        empty( $_GET['repeat_pwd']) ||
        empty( $_GET['sitename']) ||
        empty( $_GET['lang_name']) ||
        empty( $_GET['lang_code']) ||
        empty( $_GET['db_host']) ||
        empty( $_GET['db_username']) ||
        empty( $_GET['db_password']) ) 
    {
        header( "location: ./setup.php?error=empty");
        exit();
    }

    if( !LoginUtils::isValidEmail( $_GET['email'] ) ) {
        header( "location: ./setup.php?error=invalid_email");
        exit();
    }
    
    if( strcmp( $_GET['pwd'], $_GET['repeat_pwd'] ) !== 0 ) {
        header( "location: ./setup.php?error=pwdmatch");
        exit();
    }

    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/",$_GET['pwd'])) {
        header( "location: ./setup.php?error=pwd");
        exit();
    }
    
    if (!preg_match("/^[a-zA-Z-' ]*$/",$_GET['sitename'])) {
        header( "location: ./setup.php?error=sitename");
        exit();
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/",$_GET['lang_name'])) {
        header( "location: ./setup.php?error=lang_name");
        exit();
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/",$_GET['lang_code'])) {
        header( "location: ./setup.php?error=lang_code");
        exit();
    }
    
}

?>

<form action="setup.php" method="GET">
    <h3>Credentials</h3>

    <input type="text" name="email" placeholder="E-mail">
    <input type="password" name="pwd" placeholder="Password">
    <input type="password" name="repeat_pwd" placeholder="Repeat password">
    
    <h3>Site Configuration</h3>
    <input type="text" name="sitename" placeholder="Site name">
    <input type="text" name="lang_name" placeholder="Main Langauge name">
    <input type="text" name="lang_code" placeholder="Main Langauge code">
    

    <h3>Database Connection</h3>
    
    <input type="text" name="db_host" placeholder="Host">
    <input type="text" name="db_username" placeholder="Username">
    <input type="text" name="db_password" placeholder="Password">


    <button type="submit" name="submit">Create</button>
</form>


