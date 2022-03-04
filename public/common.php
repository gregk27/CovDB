<?php

function insertHeader(string $name = null){
    // Use defined name if provided and not otherwise specified
    if($name == null && defined("NAME")) $name = NAME;
    echo "<header>";
    if($name == null){
        echo "<h1><a href='/' style='text-decoration:none;color:inherit'>Covid Vaccine Database</a></h1>";
    } else {
        echo "<h1><a href='/' style='text-decoration:none;color:inherit'>CoVDB</a> - $name</h1>";
    }
    echo "</header>";
}

function insertMeta(string $name = null){
    // Use defined name if provided and not otherwise specified
    if($name == null && defined("NAME")) $name = NAME;
    if($name == null) {
        echo "<title>Covid DB</title>";
    } else {
        echo "<title>$name - CoVDB</title>";
    }
    // Output common metadata
    ?>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel='stylesheet' type='text/css' media='screen' href='/main.css'>
        <!-- Inlcude FontAwesome for icons -->
        <script src="https://kit.fontawesome.com/60394e1dab.js" crossorigin="anonymous"></script>
    <?php
}
