<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/language.php";

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
                <h1>Languages</h1>

                <div class="row">
                    <div class="col-12">
                        <table data-toggle="table" data-buttons="buttons">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th data-field="ciso" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($languages as $lang) {
                                ?>
                                    <tr>
                                        <td><?php echo $lang->getLangId(); ?></td>
                                        <td><?php echo $lang->getLangName(); ?></td>
                                        <td><?php echo $lang->getLangCode(); ?></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>


            </div>



            <script>
                window.operateEvents = {
                    'click .like': function(e, value, row) {
                        console.log('You click like action, row: ' + JSON.stringify(row));
                        window.location.href = "/admin/languages/edit/?id="+row[0];
                    },
                    'click .remove': function(e, value, row) {
                        alert('You click remove action, row: ' + JSON.stringify(row))
                    }
                }

                function operateFormatter(value, row, index) {
                    return [
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

                                window.location.href = "/admin/languages/new";

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