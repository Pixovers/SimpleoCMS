<?php

if( isset( $_GET['submit'] ) ) {
    
} else {

?>

<form action="setup.php" method="GET">
    <h3>Credentials</h3>
    <input type="text" name="email" placeholder="E-mail">
    <input type="password" name="pwd" placeholder="Password">
    <input type="password" name="pwd" placeholder="Repeat password">
    <input type="text" name="sitename" placeholder="Site name">
    <h3>Database Connection</h3>
    <input type="text" name="sitename" placeholder="">
    <input type="text" name="sitename" placeholder="Site name">


    <button type="submit" name="submit">Create</button>
</form>


<?php } ?>

