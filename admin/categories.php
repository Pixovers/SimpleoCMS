<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/category.php";

$languages = Language::getAllLangueage($_CONN);

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

            <div class="container-fluid">
                <h1>Categories</h1>
                <?php
                foreach ($languages as $lang) {
                    $cats = Category::fetchAllByLang($_CONN, $lang);
                ?>
                    <div class="row">

                        <div class="col-12">
                            <table data-toggle="table" data-buttons="buttons_<?php echo $lang->getLangCode(); ?>">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th data-field="github.name" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($cats as $cat) {
                                    ?>
                                        <tr>
                                            <td><?php echo $cat->getId(); ?></td>
                                            <td><?php echo $cat->getName(); ?></td>
                                            <td><?php echo $cat->getMetaDescription(); ?></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                <?php } ?>

            </div>


            <script>
                window.operateEvents = {
                    'click .like': function(e, value, row) {
                        window.location.href = "/admin/categories/edit/?id="+row[0];
                    },
                    'click .remove': function(e, value, row) {
                        window.location.href = "/admin/categories/delete/?id="+row[0];
                    }
                }

                function operateFormatter(value, row, index) {
                    return [
                        '<div class="right">',
                        '<a class="like" title="Edit category">',
                        '<i class="py-3 fas fa-pencil-alt"></i>',
                        '</a>  ',
                        '<a class="remove" title="Delete category">',
                        '<i class="py-3  fa fa-trash"></i>',
                        '</a>',
                        '</div>'
                    ].join('')
                }
            </script>



            <?php foreach ($languages as $lang) { ?>
            <script>
                function buttons_<?php echo $lang->getLangCode(); ?>() {
                    return {
                        btnAdd: {
                            text: 'Add new row',
                            icon: 'fa-plus',
                            event: function() {
                                window.location.href = "/admin/posts/new/?lang=<?php echo $lang->getLangCode(); ?>";
                            },
                            attributes: {
                                title: 'Add a new post'
                            }
                        }
                    }
                }
            </script>
            <?php } ?>





        </div>
    </div>




    <?php include_once "templates/footer.php"; ?>

</body>

</html>