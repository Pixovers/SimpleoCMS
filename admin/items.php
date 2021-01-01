<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/post.php";

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
                <h1>Posts</h1>
                <?php
                foreach ($languages as $lang) {
                    $posts = Post::fetchAllByLang($_CONN, $lang);
                ?>
                    <div class="row">

                        <div class="col-12">
                            <table data-toggle="table" data-buttons="buttons">
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
                                    foreach ($posts as $post) {
                                    ?>
                                        <tr>
                                            <td><?php echo $post->getId(); ?></td>
                                            <td><?php echo $post->getName(); ?></td>
                                            <td><?php echo $post->getMetaDescription(); ?></td>
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
                        alert('You click like action, row: ' + JSON.stringify(row))
                    },
                    'click .remove': function(e, value, row) {
                        alert('You click remove action, row: ' + JSON.stringify(row))
                    }
                }

                function operateFormatter(value, row, index) {
                    return [
                        '<div class="left">',
                        '<a href="https://github.com/wenzhixin/' + value + '" target="_blank">' + value + '</a>',
                        '</div>',
                        '<div class="right">',
                        '<a class="like" href="#" title="Like">',
                        '<i class="py-3 fas fa-pencil-alt"></i>',
                        '</a>  ',
                        '<a class="remove" href="#" title="Remove">',
                        '<i class="py-3  fa fa-trash"></i>',
                        '</a>',
                        '</div>'
                    ].join('')
                }
            </script>




            <script>
                function buttons() {
                    return {
                        btnAdd: {
                            text: 'Add new row',
                            icon: 'fa-plus',
                            event: function() {

                                alert('Do some stuff to e.g. add a new row')

                            },
                            attributes: {
                                title: 'Add a new row to the table'
                            }
                        }
                    }
                }
            </script>





        </div>
    </div>




    <?php include_once "templates/footer.php"; ?>

</body>

</html>