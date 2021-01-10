<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/language.php";

if (isset($_ACTION)) {
    switch ($_ACTION) {
        case "new":
            if (isset($_POST['submit'])) {
                Language::addNew(
                    $_CONN,
                    $_POST['LangName'],
                    $_POST['LangCode']
                );

                header("location: /admin/languages");
                exit();
            }
            break;

        case "edit":
            if (isset($_GET['id'])) {
                $lang = Language::byId($_CONN, $_GET['id']);
                if ($lang) {
                    if (isset($_POST['submit'])) {
                        $lang->setLangName($_POST['LangName']);
                        $lang->setLangCode($_POST['LangCode']);
                        $lang->update($_CONN);
                        header("location: /admin/languages");
                        exit();
                    }
                } else {
                    Utils::error_404();
                }
            } else {
                Utils::error_404();
            }
            break;

        case "delete":
            if (Language::delete($_CONN, $_GET['id'])) {
                header("location: /admin/languages");
                exit();
            } else {
                header("location: /admin/languages/edit/?id=" . $_GET['id'] . "&error=delete");
                exit();
            }
            break;

        default:
            Utils::error_404();
    }
} else {
    Utils::error_404();
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
                                <h5 class="card-title">
                                    <?php
                                    if ($_ACTION == "new") echo "Create new language";
                                    else echo "Edit language";
                                    ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group col">
                                    <label for="LangName">Language name</label>
                                    <input type="text" class="form-control" id="LangName" name="LangName" value="<?php if ($_ACTION == "edit") echo $lang->getLangName(); ?>" required>
                                </div>

                                <div class="form-group col-4">
                                    <label for="LangCode">Language code</label>
                                    <input type="text" class="form-control" id="LangCode" name="LangCode" value="<?php if ($_ACTION == "edit") echo $lang->getLangCode(); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button class="btn btn-primary" type="submit" name="submit">
                                <?php
                                if ($_ACTION == "new") echo "Create";
                                else echo "Update";
                                ?>
                            </button>

                            <?php if ($_ACTION == "edit") { ?>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteLangaugeModal">Delete</button>
                                <div class="modal fade" id="deleteLangaugeModal" tabindex="-1" aria-labelledby="link" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete langauge</h5>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this language?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" onclick="window.location.href='/admin/languages/delete/?id=<?php echo $lang->getLangId() ?>';">Yes, proceed</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">no</button> </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </form>
                </div>
                <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="link" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Error</h5>
                            </div>
                            <div class="modal-body">
                                <p>Couldn't delete this language: other elements (such as posts and categories) references this language. </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Okay</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--page content-->
            </div>
        </div>
    </div>

    <?php include_once "templates/footer.php"; ?>

    <?php if (isset($_GET['error'])) { ?>
        <script>
            console.log("dsfd");
            $('#errorModal').modal('show');
        </script>
    <?php } ?>

</body>

</html>