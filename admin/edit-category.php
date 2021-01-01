<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/category.php";

$cat = Category::byId( $_CONN, $_GET['id'] );

if (isset($_POST['submit'])) {
    $category_lang = Language::byName($_CONN, $_POST['languageSelect']);

    $cat->setName( $_POST['TitleInput'] );
    $cat->setUrl( $_POST['SlugInput'] );
    $cat->setMetaTitle( $_POST['metaTitleInput'] );
    $cat->setMetaDescription( $_POST['metaDescriptionInput'] );
    $cat->update( $_CONN );
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
                                    <input type="text" class="form-control" id="TitleInput" name="TitleInput" value="<?php echo $cat->getName(); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mt-2 mb-2">
                            <div class="card-body">
                                <h5 class="card-title">Language</h5>
                                <div class="form-group">
                                <input type="text" class="form-control" id="LangName" name="LangName" value="<?php echo $cat->getLang()->getLangName(); ?>" readonly>
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
                                    <input type="text" class="form-control" id="metaTitleInput" name="metaTitleInput" value="<?php echo $cat->getMetaTitle(); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="SlugInput">URL slug</label>
                                    <input type="text" class="form-control" id="SlugInput" name="SlugInput" value="<?php echo $cat->getUrl(); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="metaDescriptionInput">Meta description</label>
                                    <textarea class="form-control" id="metaDescriptionInput" name="metaDescriptionInput" rows="3"><?php echo $cat->getMetaDescription(); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit" name="submit">Update</button>
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