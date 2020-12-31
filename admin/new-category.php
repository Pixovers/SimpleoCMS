<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/category.php";
if (isset($_POST['submit'])) {
    $category_lang = Language::byName($_CONN, $_POST['languageSelect']);
    Category::addNew(
        $_CONN,
        $_POST['TitleInput'],
        $_POST['DescriptionInput'],
        $category_lang,
        NULL,
        $_POST['metaTitleInput'],
        $_POST['metaDescriptionInput']
    );
    echo 3;
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
                                <h5 class="card-title">Category</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="TitleInput">Category Title</label>
                                    <input type="text" class="form-control" id="TitleInput" name="TitleInput" required>
                                </div>

                                <div class="form-group">
                                    <label for="DescriptionInput">Category description</label>
                                    <textarea class="form-control" id="DescriptionInput" name="DescriptionInput" rows="6"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mt-2 mb-2">
                            <div class="card-body">
                                <h5 class="card-title">language</h5>
                                <div class="form-group">
                                    <select class="form-control" id="languageSelect" name="languageSelect">
                                        <?php
                                        $languages = Language::getAllLangueage($_CONN);
                                        
                                        foreach( $languages as $lang ) {
                                            echo '<option>'.$lang->getLangName().'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-light mt-3 mb-3 shadow-sm ">
                            <div class="card-header">
                                <h5 class="card-title">SEO</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="metaTitleInput">Meta Title</label>
                                    <input type="text" class="form-control" id="metaTitleInput" name="metaTitleInput">
                                </div>

                                <div class="form-group">
                                    <label for="metaDescriptionInput">Meta description</label>
                                    <textarea class="form-control" id="metaDescriptionInput" name="metaDescriptionInput" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit" name="submit">Create</button>
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