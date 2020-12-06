<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/control_panel/login_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/db_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/str_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/utils/lang_utils.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/category.php";


if (isset($_GET['error'])) {
    switch ($_GET['error']) {
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
        case "sitename":
            $error_message = "Invalid sitename";
            break;
        case "lang_name":
            $error_message = "Invalid language name";
            break;
        case "lang_code":
            $error_message = "Invalid langguage code";
            break;
        case "db_conn":
            $error_message = "Coudn't enstabilish a MySql connection. Check MySql credentials";
            break;
        default:
            $error_message = "Unknown Error";
    }

} else if (isset($_GET['submit'])) {

    if (
        empty($_GET['email']) ||
        empty($_GET['pwd']) ||
        empty($_GET['repeat_pwd']) ||
        empty($_GET['sitename']) ||
        empty($_GET['lang_name']) ||
        empty($_GET['lang_code']) ||
        empty($_GET['db_host']) ||
        empty($_GET['db_database']) ||
        empty($_GET['db_username'])
    ) {
        header("location: ./setup.php?error=empty&" . http_build_query($_GET));
        exit();
    }

    if (!LoginUtils::isValidEmail($_GET['email'])) {
        header("location: ./setup.php?error=invalid_email&" . http_build_query($_GET));
        exit();
    }

    if (strcmp($_GET['pwd'], $_GET['repeat_pwd']) !== 0) {
        header("location: ./setup.php?error=pwdmatch&" . http_build_query($_GET));
        exit();
    }

    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $_GET['pwd'])) {
        header("location: ./setup.php?error=pwd&" . http_build_query($_GET));
        exit();
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $_GET['sitename'])) {
        header("location: ./setup.php?error=sitename&" . http_build_query($_GET));
        exit();
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $_GET['lang_name'])) {
        header("location: ./setup.php?error=lang_name&" . http_build_query($_GET));
        exit();
    }

    if (!preg_match("/^[a-zA-Z-' ]*$/", $_GET['lang_code'])) {
        header("location: ./setup.php?error=lang_code&" . http_build_query($_GET));
        exit();
    }


    if (!DBUtils::CheckConnection($_GET['db_host'], $_GET['db_username'], $_GET['db_password'], $_GET['db_database'])) {
        header("location: ./setup.php?error=db_conn&" . http_build_query($_GET));
        exit();
    }

    //--- No errors     ---
    //--- creating site ---

    $database = $_GET['db_database'];

    //saving mysql credentials
    DBUtils::saveCredentials($_GET['db_host'], $_GET['db_username'], $_GET['db_password'], $database);
    $sql_text = <<<EOD
    CREATE DATABASE IF NOT EXISTS $database;
    USE $database;
    
    DROP TABLE IF EXISTS `post`;
    DROP TABLE IF EXISTS `category`;
    DROP TABLE IF EXISTS `translates`;
    DROP TABLE IF EXISTS `users`;
    DROP TABLE IF EXISTS `language`;
    DROP TABLE IF EXISTS `config`;

    CREATE TABLE IF NOT EXISTS `language` (
      `lang_id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
      `lang_name` VARCHAR(32) NOT NULL,
      `lang_code` VARCHAR(5) NOT NULL )
    ENGINE = InnoDB;
    
    CREATE TABLE IF NOT EXISTS `category` (
      `category_id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
      `cat_name` TEXT NOT NULL,
      `cat_url` TEXT NOT NULL,
      `cat_lang_id` INT NOT NULL,
      `cat_lang_ref` INT DEFAULT NULL,
      `cat_meta_title` VARCHAR(128) NOT NULL,
      `cat_meta_description` VARCHAR(256) NOT NULL,
      FOREIGN KEY (cat_lang_id) REFERENCES `language`(lang_id),
      FOREIGN KEY (cat_lang_ref) REFERENCES `category`(category_id)  )
    ENGINE = InnoDB;
    
    CREATE TABLE IF NOT EXISTS `post` (
      `post_id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
      `post_name` TEXT NOT NULL,
      `post_content` MEDIUMTEXT NOT NULL,
      `post_category_id` INT NOT NULL,
      `post_lang_id` INT NOT NULL,
      `post_lang_ref` INT NOT NULL,
      `post_url` TEXT NOT NULL,
      `post_meta_title` VARCHAR(128) NOT NULL,
      `post_meta_description` VARCHAR(256) NOT NULL,
      `post_status` INT NOT NULL,
      FOREIGN KEY (post_category_id) REFERENCES `category`(category_id),
      FOREIGN KEY (post_lang_id) REFERENCES `language`(lang_id),
      FOREIGN KEY (post_lang_ref) REFERENCES `post`(post_id)  )
    ENGINE = InnoDB;

    CREATE TABLE IF NOT EXISTS `users` (
        `user_id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `user_email` TEXT NOT NULL,
        `user_pwd` MEDIUMTEXT NULL )
    ENGINE = InnoDB;

    CREATE TABLE IF NOT EXISTS `translates` (
        `translate_id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `translate_value` MEDIUMTEXT NOT NULL,
        `translate_lang_id` INT NOT NULL,
        `translate_lang_ref` INT NOT NULL,
        FOREIGN KEY (translate_lang_id) REFERENCES `language`(lang_id),
        FOREIGN KEY (translate_lang_ref) REFERENCES `translates`(translate_id) )
    ENGINE = InnoDB;

    CREATE TABLE IF NOT EXISTS `config` (
        `config_id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `config_key` VARCHAR(256) NOT NULL,
        `config_value` VARCHAR(256) )
    ENGINE = InnoDB;
EOD;

    $conn = DBUtils::createConnection(false);
    $conn->multi_query($sql_text);

    do {
        if ($result = $conn->store_result()) {
            $result->free_result();
        }
    } while ($conn->next_result());

    $sql_text = "INSERT INTO language (lang_name, lang_code) VALUES (\"" . $_GET['lang_name'] . "\",\"" . $_GET['lang_code'] . "\")";
    $conn->query($sql_text);

    $sql_text = "INSERT INTO config (config_key, config_value) VALUES ('MAIN_LANGUAGE',\"" . $_GET['lang_code'] . "\")";
    $conn->query($sql_text);

    $sql_text = "INSERT INTO config (config_key, config_value) VALUES ('SITENAME',\"" . $_GET['sitename'] . "\")";
    $conn->query($sql_text);


    $hashed_pwd = password_hash($_GET['pwd'], PASSWORD_DEFAULT);

    $sql_text = "INSERT INTO users (user_email, user_pwd) VALUES (\"" . $_GET['email'] . "\",\"$hashed_pwd\")";
    $conn->query($sql_text);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "templates/head.php"; ?>
</head>

<body>
    <div class="wrapper">
        <?php include_once "templates/sidebar.php"; ?>

        <!-- Page Content  -->
        <div id="content">

            <?php include_once "templates/navbar.php"; ?>

            <div class="container-fluid">
                <div class="row">

                    <div class="col">
                        <div class="card text-white bg-dark mb-3 py-4">
                            <div class="card-header">Create new website</div>
                            <div class="card-body">
                                
                                <?php if( isset($error_message) ) { ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Error!</strong> <?php echo $error_message; ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                                <?php } ?>

                                <form action="setup.php" method="GET">
                                    <br>
                                    <p class="text-center h3">Account credentials</p><br>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="email_input">E-mail address:</label></div>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" id="email_input" name="email" placeholder="E-mail" value="<?php if (isset($_GET['email'])) echo $_GET['email']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="pwd_input">Password</label></div>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="password" id="pwd_input" name="pwd" placeholder="Password" value="<?php if (isset($_GET['pwd'])) echo $_GET['pwd']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="repeat_pwd_input">Repeat password</label></div>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="password" id="repeat_pwd_input" name="repeat_pwd" placeholder="Repeat password" value="<?php if (isset($_GET['repeat_pwd'])) echo $_GET['repeat_pwd']; ?>">
                                        </div>
                                    </div>
                                    <hr>
                                    <br>
                                    <p class="text-center h3">Site configuration</p><br>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="sitename_input">Sitename</label></div>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" id="sitename_input" name="sitename" placeholder="Site name" value="<?php if (isset($_GET['sitename'])) echo $_GET['sitename']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="lang_name_input">Language</label></div>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" id="lang_name_input" name="lang_name" placeholder="Main Langauge name" value="<?php if (isset($_GET['lang_name'])) echo $_GET['lang_name']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="lang_code_input">Language code</label></div>
                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" id="lang_code_input" name="lang_code" placeholder="Main Langauge code" value="<?php if (isset($_GET['lang_code'])) echo $_GET['lang_code']; ?>">
                                        </div>
                                    </div>
                                    <hr>
                                    <br>
                                    <p class="text-center h3">Database access</p>
                                    <br>
                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="db_host_input">Hostname</label></div>
                                        <div class="col-sm-6"><input class="form-control" type="text" id="db_host_input" name="db_host" placeholder="Host" value="<?php if (isset($_GET['db_host'])) echo $_GET['db_host']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="db_username_input">Username</label></div>
                                        <div class="col-sm-6"><input class="form-control" type="text" id="db_username_input" name="db_username" placeholder="Username" value="<?php if (isset($_GET['db_username'])) echo $_GET['db_username']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="db_database_input">Database</label></div>
                                        <div class="col-sm-6"><input class="form-control" type="text" id="db_database_input" name="db_database" placeholder="Database" value="<?php if (isset($_GET['db_database'])) echo $_GET['db_database']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row justify-content-center">
                                        <div class="col-sm-2"><label for="db_password_input">Password</label></div>
                                        <div class="col-sm-6"><input class="form-control" type="password" id="db_password_input" name="db_password" placeholder="Password" value="<?php if (isset($_GET['db_password'])) echo $_GET['db_password']; ?>">
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="text-right">
                                        <button class="btn btn-primary" type="submit" name="submit">Create</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "templates/footer.php"; ?>

</body>

</html>