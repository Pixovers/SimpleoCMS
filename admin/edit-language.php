<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/language.php";

$lang = Language::byId( $_CONN, $_GET['id'] );

if (isset($_POST['submit'])) {
    
    $lang->setLangName($_POST['LangName']);
    $lang->setLangCode($_POST['LangCode']);
    $lang->update($_CONN);
    header("location: /admin/languages");
    exit();
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

        <div id="content">

            <?php include_once "templates/navbar.php"; ?>

            <div class="container-fluid mb-3">
                <!--page content-->
                <div class="col-8" style="float: none;margin: 0 auto;">
                    <form method="POST">
                        <div class="card bg-light mt-3 mb-3 shadow-sm ">
                            <div class="card-header">
                                <h5 class="card-title">New Language</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group col">
                                    <label for="LangName">Language name</label>
                                    <input type="text" class="form-control" id="LangName" name="LangName" value="<?php echo $lang->getLangName(); ?>" required>
                                </div>

                                <div class="form-group col-4">
                                    <label for="LangCode">Language code</label>
                                    <input type="text" class="form-control" id="LangCode" name="LangCode" value="<?php echo $lang->getLangCode(); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit" name="submit">Refresh</button>
                        </div>
                    </form>
                </div>
                <!--page content-->
            </div>



        </div>
    </div>

    <?php include_once "templates/footer.php"; ?>

</body>

</html>