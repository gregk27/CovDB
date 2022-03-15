<!DOCTYPE html>
<html>
<?php 
    define("NAME", "Vaccine Availability");
    include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
    include_once(BACKEND_DIR."vaccines.php");
    include_once(BACKEND_DIR."sites.php");
    include_once(COMMON_ELEMENTS);
?>
<head>
    <?php insertMeta(); ?>
    <script src='main.js'></script>
</head>
<body>
    <?php
        insertHeader();
    ?>
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
            foreach(getSitesWithVax($_GET["vax"]) as $s){
                echo '<a class="panel"><i class="fa-solid fa-location-dot"></i><h2>'.$s["site"]->name." - ".$s["doses"].'</h2><p>'.$s["site"]->street.' '.$s["site"]->city.', '.$s["site"]->province.'</p></a>';
            }
        ?>
    </section>
    <?php } ?>
</body>

</html>