<?php

function insertHeader(string $name = null){
    // Use defined name if provided and not otherwise specified
    if($name == null && defined("NAME")) $name = NAME;
    ?>
    <header>
        <h1>
            <a href='/'><img src='/public/logo.svg'></img></a>
            <span style='margin-left:0.5em'>
                <?= $name == null ? "Covid Vaccine Database" : $name ?>
            </span>
        </h1>
        <nav style="margin-left:-1em">
            <a href="/public/covid.php">Home</a>
        </nav>
    </header>
    <?php
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
        <link rel='stylesheet' type='text/css' media='screen' href='/public/main.css'>
        <!-- Inlcude FontAwesome for icons -->
        <script src="https://kit.fontawesome.com/60394e1dab.js" crossorigin="anonymous"></script>
    <?php
}
