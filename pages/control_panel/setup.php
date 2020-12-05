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

    echo $error_message;
} else if (isset($_GET['submit'])) {

    if (
        empty($_GET['email']) ||
        empty($_GET['pwd']) ||
        empty($_GET['repeat_pwd']) ||
        empty($_GET['sitename']) ||
        empty($_GET['lang_name']) ||
        empty($_GET['lang_code']) ||
        empty($_GET['db_host']) ||
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


    if (!DBUtils::CheckConnection($_GET['db_host'], $_GET['db_username'], $_GET['db_password'])) {
        header("location: ./setup.php?error=db_conn&" . http_build_query($_GET));
        exit();
    }

    //--- No errors ---

    //--- creating site ---

    $database = StringUtils::sanitize($_GET['sitename']);

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>





    <style>
        @import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";

        body {
            font-family: 'Poppins', sans-serif;
            background: #495057;

        }

        p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1em;
            font-weight: 300;
            line-height: 1.7em;
            color: #999;
        }

        a,
        a:hover,
        a:focus {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s;
        }

        .navbar {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 40px;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .navbar-btn {
            box-shadow: none;
            outline: none !important;
            border: none;
        }

        .line {
            width: 100%;
            height: 1px;
            border-bottom: 1px dashed #ddd;
            margin: 40px 0;
        }

        i,
        span {
            display: inline-block;
        }

        .wrapper {
            display: flex;
            align-items: stretch;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #545b62;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar.active {
            min-width: 80px;
            max-width: 80px;
            text-align: center;
        }

        #sidebar.active .sidebar-header h3,
        #sidebar.active .CTAs {
            display: none;
        }

        #sidebar.active .sidebar-header strong {
            display: block;
        }

        #sidebar ul li a {
            text-align: left;
        }

        #sidebar.active ul li a {
            padding: 20px 10px;
            text-align: center;
            font-size: 0.85em;
        }

        #sidebar.active ul li a i {
            margin-right: 0;
            display: block;
            font-size: 1.8em;
            margin-bottom: 5px;
        }

        #sidebar.active ul ul a {
            padding: 10px !important;
        }

        #sidebar.active .dropdown-toggle::after {
            top: auto;
            bottom: 10px;
            right: 50%;
            -webkit-transform: translateX(50%);
            -ms-transform: translateX(50%);
            transform: translateX(50%);
        }

        #sidebar .sidebar-header {
            padding: 13px;
            background: #343a40;
        }

        #sidebar .sidebar-header strong {
            display: none;
            font-size: 1.8em;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #47748b;
        }

        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
        }

        #sidebar ul li a:hover {
            color: #47748b;
            background: #fff;
        }

        #sidebar ul li a i {
            margin-right: 10px;
        }

        #sidebar ul li.active>a,
        a[aria-expanded="true"] {
            color: #fff;
            background: #dc3545;
        }

        a[data-toggle="collapse"] {
            position: relative;
        }

        .dropdown-toggle::after {
            display: block;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }

        ul ul a {
            font-size: 0.9em !important;
            padding-left: 30px !important;
            background: #343a40;
        }

        ul.CTAs {
            padding: 20px;
        }

        ul.CTAs a {
            text-align: center;
            font-size: 0.9em !important;
            display: block;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        a.download {
            background: #fff;
            color: #7386D5;
        }

        a.article,
        a.article:hover {
            background: #6d7fcc !important;
            color: #fff !important;
        }


        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
        }


        @media (max-width: 768px) {
            #sidebar {
                min-width: 80px;
                max-width: 80px;
                text-align: center;
                margin-left: -80px !important;
            }

            .dropdown-toggle::after {
                top: auto;
                bottom: 10px;
                right: 50%;
                -webkit-transform: translateX(50%);
                -ms-transform: translateX(50%);
                transform: translateX(50%);
            }

            #sidebar.active {
                margin-left: 0 !important;
            }

            #sidebar .sidebar-header h3,
            #sidebar .CTAs {
                display: none;
            }

            #sidebar .sidebar-header strong {
                display: block;
            }

            #sidebar ul li a {
                padding: 20px 10px;
            }

            #sidebar ul li a span {
                font-size: 0.85em;
            }

            #sidebar ul li a i {
                margin-right: 0;
                display: block;
            }

            #sidebar ul ul a {
                padding: 10px !important;
            }

            #sidebar ul li a i {
                font-size: 1.3em;
            }

            #sidebar {
                margin-left: 0;
            }

            #sidebarCollapse span {
                display: none;
            }
        }
    </style>


    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Simpleo Cms</h3>
                <strong>SC</strong>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-home"></i>
                        Dash
                    </a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="#">Home 1</a>
                        </li>
                        <li>
                            <a href="#">Home 2</a>
                        </li>
                        <li>
                            <a href="#">Home 3</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-briefcase"></i>
                        About
                    </a>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-copy"></i>
                        Pages
                    </a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#">Page 1</a>
                        </li>
                        <li>
                            <a href="#">Page 2</a>
                        </li>
                        <li>
                            <a href="#">Page 3</a>
                        </li>
                    </ul>
                </li>
            </ul>

        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-dark">
                <div class="container-fluid">



                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Espandi</span>
                    </button>

                </div>
            </nav>


            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <div class="card text-white bg-dark mb-3 py-4">
                            <div class="card-header">Create new website con vimbing</div>
                            <div class="card-body">
                                <form action="setup.php" method="GET">
                                    <h3>Credentials</h3>

                                    <input type="text" name="email" placeholder="E-mail" value="<?php if (isset($_GET['email'])) echo $_GET['email']; ?>">
                                    <input type="password" name="pwd" placeholder="Password" value="<?php if (isset($_GET['pwd'])) echo $_GET['pwd']; ?>">
                                    <input type="password" name="repeat_pwd" placeholder="Repeat password" value="<?php if (isset($_GET['repeat_pwd'])) echo $_GET['repeat_pwd']; ?>">
                                    <hr>
                                    <h3>Site Configuration</h3>
                                    <input type="text" name="sitename" placeholder="Site name" value="<?php if (isset($_GET['sitename'])) echo $_GET['sitename']; ?>">
                                    <input type="text" name="lang_name" placeholder="Main Langauge name" value="<?php if (isset($_GET['lang_name'])) echo $_GET['lang_name']; ?>">
                                    <input type="text" name="lang_code" placeholder="Main Langauge code" value="<?php if (isset($_GET['lang_code'])) echo $_GET['lang_code']; ?>">

                                    <hr>
                                    <h3>Database Connection</h3>

                                    <input type="text" name="db_host" placeholder="Host" value="<?php if (isset($_GET['db_host'])) echo $_GET['db_host']; ?>">
                                    <input type="text" name="db_username" placeholder="Username" value="<?php if (isset($_GET['db_username'])) echo $_GET['db_username']; ?>">
                                    <input type="password" name="db_password" placeholder="Password" value="<?php if (isset($_GET['db_password'])) echo $_GET['db_password']; ?>">

                                    <hr>
                                    <div class="text-right">
                                        <button type="submit" name="submit">Create</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-6">

                        <div class="card text-white bg-dark mb-4 py-4">
                            <div class="card-header">Ciao dVimbing :)</div>
                            <div class="card-body">
                                <form action="setup.php" method="GET">
                                    <h3>Credentials</h3>

                                    <input type="text" name="email" placeholder="E-mail" value="<?php if (isset($_GET['email'])) echo $_GET['email']; ?>">
                                    <input type="password" name="pwd" placeholder="Password" value="<?php if (isset($_GET['pwd'])) echo $_GET['pwd']; ?>">
                                    <input type="password" name="repeat_pwd" placeholder="Repeat password" value="<?php if (isset($_GET['repeat_pwd'])) echo $_GET['repeat_pwd']; ?>">
                                    <hr>
                                    <h3>Site Configuration</h3>
                                    <input type="text" name="sitename" placeholder="Site name" value="<?php if (isset($_GET['sitename'])) echo $_GET['sitename']; ?>">
                                    <input type="text" name="lang_name" placeholder="Main Langauge name" value="<?php if (isset($_GET['lang_name'])) echo $_GET['lang_name']; ?>">
                                    <input type="text" name="lang_code" placeholder="Main Langauge code" value="<?php if (isset($_GET['lang_code'])) echo $_GET['lang_code']; ?>">

                                    <hr>
                                    <h3>Database Connection</h3>

                                    <input type="text" name="db_host" placeholder="Host" value="<?php if (isset($_GET['db_host'])) echo $_GET['db_host']; ?>">
                                    <input type="text" name="db_username" placeholder="Username" value="<?php if (isset($_GET['db_username'])) echo $_GET['db_username']; ?>">
                                    <input type="password" name="db_password" placeholder="Password" value="<?php if (isset($_GET['db_password'])) echo $_GET['db_password']; ?>">

                                    <hr>
                                    <div class="text-right">
                                        <button type="submit" name="submit">Create</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>












    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>



<script>
    $(document).ready(function() {

        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
        });

    });
</script>

</html>