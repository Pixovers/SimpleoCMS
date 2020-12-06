<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "templates/head.php"; ?>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.css">
    <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>

</head>


<body>
    <div class="wrapper">
        <?php include_once "templates/sidebar.php"; ?>

        <div id="content">

            <?php include_once "templates/navbar.php"; ?>



            <div class="container-fluid">

<div class="row">
<div class="col-3 py-2">
<button type="button py-2" class="btn btn-success">Add new Items</button>
<button type="button py-2" class="btn btn-danger">Remove Items</button>

</div>

</div>
                <div class="row">
                    <div class="col-12 py-2">

                        <div class="card zoom text-white bg-dark mb-3">
                            <div class="card-header">Header</div>
                            <div class="card-body">




                                <table id="table" data-toggle="bootstrap-table" data-search="true" data-url="json/data1.json" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="" data-field="id">
                                                <div class="th-inner ">ID</div>
                                                <div class="fht-cell"></div>
                                            </th>
                                            <th style="" data-field="name">
                                                <div class="th-inner ">Item Name</div>
                                                <div class="fht-cell"></div>
                                            </th>
                                            <th style="" data-field="price">
                                                <div class="th-inner ">Item Price</div>
                                                <div class="fht-cell"></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-index="0">
                                            <td>0</td>
                                            <td>Item 0</td>
                                            <td>$0</td>
                                        </tr>
                                        <tr data-index="1">
                                            <td>1</td>
                                            <td>Item 1</td>
                                            <td>$1</td>
                                        </tr>
                                        <tr data-index="2">
                                            <td>2</td>
                                            <td>Item 2</td>
                                            <td>$2</td>
                                        </tr>
                                        <tr data-index="3">
                                            <td>3</td>
                                            <td>Item 3</td>
                                            <td>$3</td>
                                        </tr>
                                        <tr data-index="4">
                                            <td>4</td>
                                            <td>Item 4</td>
                                            <td>$4</td>
                                        </tr>
                                        <tr data-index="5">
                                            <td>5</td>
                                            <td>Item 5</td>
                                            <td>$5</td>
                                        </tr>


                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>



                </div>
            </div>



        </div>
    </div>
    <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
    <?php include_once "templates/footer.php"; ?>

</body>

</html>