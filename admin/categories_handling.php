<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/category.php";




if (isset($_ACTION)) {
    switch ($_ACTION) {
        case "new":
            

            if (isset($_POST['submit'])) {
                $category_lang = Language::byName($_CONN, $_POST['languageSelect']);
                Category::addNew(
                    $_CONN,
                    $_POST['TitleInput'],
                    $_POST['SlugInput'],
                    $category_lang,
                    NULL,
                    $_POST['metaTitleInput'],
                    $_POST['metaDescriptionInput']
                );

                header("location: /admin/categories");
                exit();
            }
            break;

        case "edit":
            if (isset($_GET['id'])) {
                $cat = Category::byId($_CONN, $_GET['id']);
                if ($cat) {
                    if (isset($_POST['submit'])) {

                        $cat->setName($_POST['TitleInput']);
                        $cat->setUrl($_POST['SlugInput']);
                        $cat->setMetaTitle($_POST['metaTitleInput']);
                        $cat->setMetaDescription($_POST['metaDescriptionInput']);
                        $cat->update($_CONN);
                    }
                } else {
                    Utils::error_404();
                }
            } else {
                Utils::error_404();
            }
            break;

        case "delete":
            if (Category::delete($_CONN, $_GET['id'])) {
                header("location: /admin/categories");
                exit();
            } else {
                header("location: /admin/categories/edit/?id=" . $_GET['id'] . "&error=delete");
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
                                <h5 class="card-title">Category</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="TitleInput">Category Title</label>
                                    <input type="text" class="form-control" id="TitleInput" name="TitleInput" value="<?php if ($_ACTION == "edit") echo $cat->getName(); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mt-2 mb-2">
                            <div class="card-body">
                                <h5 class="card-title">language</h5>
                                <div class="form-group">
                                    <?php if ($_ACTION == "new") { ?>
                                        <select class="form-control" id="languageSelect" name="languageSelect">
                                            <?php
                                            $languages = Language::getAllLangueage($_CONN);

                                            foreach ($languages as $lang) {
                                                echo '<option>' . $lang->getLangName() . '</option>';
                                            }
                                            ?>
                                        </select>
                                    <?php } else if ($_ACTION == "edit") { ?>
                                        <input type="text" class="form-control" id="LangName" name="LangName" value="<?php echo $cat->getLang()->getLangName(); ?>" readonly>
                                    <?php } ?>
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
                                    <input type="text" class="form-control" id="metaTitleInput" name="metaTitleInput" value="<?php if ($_ACTION == "edit") echo $cat->getMetaTitle(); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="SlugInput">URL slug</label>
                                    <input type="text" class="form-control" id="SlugInput" name="SlugInput" value="<?php if ($_ACTION == "edit") echo $cat->getUrl(); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="metaDescriptionInput">Meta description</label>
                                    <textarea class="form-control" id="metaDescriptionInput" name="metaDescriptionInput" rows="3"><?php if ($_ACTION == "edit") echo $cat->getMetaDescription(); ?></textarea>
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
                                                <p>Are you sure you want to delete this category?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" onclick="window.location.href='/admin/categories/delete/?id=<?php echo $cat->getId() ?>';">Yes, proceed</button>
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
                                <p>Couldn't delete this category: other elements (such as posts) references this category. </p>
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