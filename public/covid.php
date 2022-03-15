<!DOCTYPE html>
<html>
<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
    include_once(BACKEND_DIR."vaccines.php");
    include_once(COMMON_ELEMENTS);
?>
<head>
    <?php insertMeta(); ?>
    <script src='main.js'></script>
    <style>
        .panel {
            max-width: 25%;
        }
    </style>
</head>
<body>
    <?php insertHeader(); ?>
    <section class="panelgrid">
        <a class="panel clickable" href="/record">
            <i class="fa-solid fa-file-medical"></i>
            <h2>Record Vaccination</h2>
            <p>Submit a record of your vaccination</p>
        </a>
        <a class="panel clickable" href="availability">
            <i class="fa-solid fa-magnifying-glass"></i>
            <h2>Search for Vaccine</h2>
            <p>Search sites to find a vaccine</p>
        </a>
        <a class="panel clickable" href="patients">
            <i class="fa-solid fa-syringe"></i>
            <h2>Check Status</h2>
            <p>Check your vaccination status</p>
        </a>
        <a class="panel clickable" href="workers">
            <i class="fa-solid fa-user-doctor"></i>
            <h2>Worker Lookup</h2>
            <p>See who's working at a site</p>
        </a>
    </section>
</body>

</html>