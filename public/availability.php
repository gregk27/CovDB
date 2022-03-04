<!DOCTYPE html>
<html>
<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
    include_once(BACKEND_DIR."vaccines.php");
?>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>COVID DB</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <!-- Inlcude FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/60394e1dab.js" crossorigin="anonymous"></script>
    <script src='main.js'></script>
</head>
<body>
    <header>
        <h1>CoVDB - Availability</h1>
    </header>
    <section class="panelgrid">
        <?php
            foreach(getCompanies() as $c){
                // Show currently selected in secondary colour
                if(isset($_GET["vax"]) && $_GET["vax"]==$c->name){
                    echo '<a class="panel clickable" href="?vax='.urlencode($c->name).'" style="background-color:var(--colour-secondary);color:var(--colour-on-secondary)"><h2>'.$c->name.'</h2></a>';
                } else {
                    echo '<a class="panel clickable" href="?vax='.urlencode($c->name).'"><h2>'.$c->name.'</h2></a>';
                }
            }
        ?>
    </section>
    <?php if(isset($_GET["vax"])){ ?>
    <section class="panelgrid">
        <?php
            foreach(getSites($_GET["vax"]) as $s){
                echo '<a class="panel"><h2>'.$s["site"]->name." - ".$s["doses"].'</h2><p>'.$s["site"]->street.' '.$s["site"]->city.', '.$s["site"]->province.'</p></a>';
            }
        ?>
    </section>
    <?php } ?>
</body>

</html>