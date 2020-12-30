<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "templates/head.php"; ?>



    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.css">

</head>


<body>
    <div class="wrapper">
        <?php include_once "templates/sidebar.php"; ?>

        <div id="content">

            <?php include_once "templates/navbar.php"; ?>

            <div class="container-fluid">
                <div class="row">

                    <div class="col-12">
                        <table data-toggle="table" data-buttons="buttons">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nome</th>
                                    <th>Descrizione</th>
                                    <th data-field="github.name" data-formatter="operateFormatter" data-events="operateEvents">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0</td>
                                    <td>Articol asdasfgserawefsfs fdasf fo 1</td>
                                    <td>Descrizione 1jsdjafjdfjfsdafsedfdfhsdk1jsdjafjdfjfsdafsedfdfhsdkjfhsdasdasssssssdasdasdsadskjdfjfhsdasdasssssssdasdasdsadskjdf</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Articolo 2</td>
                                    <td>Descrizione 1jsdjafjdfjfsdafsedfdfh1jsdjafjdfjfsdafsedfdfhsdkjfhsdasdasssssssdasdasdsadskjdfsdkjfhskjdf</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

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
    </div>
    </div>
    </div>
    </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
    <?php include_once "templates/footer.php"; ?>

</body>

</html>