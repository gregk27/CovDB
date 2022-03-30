<!DOCTYPE html>
<html>
<?php 
    define("NAME", "Patient Lookup");
    include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
    include_once(BACKEND_DIR."/patients.php");
    include_once(COMMON_ELEMENTS);
?>
<head>
    <?php insertMeta(); ?>
    <link rel='stylesheet' type='text/css' href='/public/listpage.css'>
</head>
<body>
    <?php insertHeader(); ?>
    <div id="content">
        <section id="sidebar">
        <?php foreach(getPatients("lastName") as $p):
            $selected = "";
            if(isset($_GET['p']) && $_GET['p'] == $p->OHIP){
                $selected = "selected";
            }
            ?>
            <a class="panel clickable <?= $selected ?>" href="?p=<?=$p->OHIP?>" style="width:70%; margin-left:auto; margin-right:auto">
                <i class="fa-solid fa-user"></i>
                <h2><?= $p->firstName." ".$p->lastName ?></h2>
                <p><?= $p->OHIP ?></p>
            </a>
        <?php endforeach; ?>
        </section>
        <?php if(isset($_GET['p'])): 
             extract(getPatientAndVaxInfo($_GET['p']));
             ?>
        <section id="info">
            <h2>Vaccination status for <?="$patient->firstName $patient->lastName"?>:</h2>
            <h3>Total doses received: <?= $numDoses?></h3>
            <?php if(isset($_COOKIE['view']) && $_COOKIE['view']=='table'): ?>
                <div>
                   <table>
                    <tr><th>Company</th><th>Lot</th><th>Date/Time</th></tr>
                    <?php foreach($vaccinations as $v):?>
                    <tr>
                        <td><?=$v["lot"]->company?></td>
                        <td><?=$v["lot"]->number?></td>
                        <td><?=$v["datetime"]?></td>
                    </tr>
                    <?php endforeach; ?> 
                    </table>
                </div>
                <button onclick="document.cookie='view=;';window.location.reload()">View Panels</button>
            <?php else: ?>
                <div class="panelGrid">
                    <?php foreach($vaccinations as $v):?>
                    <div class="panel">
                        <i class="fa-solid fa-syringe"></i>
                        <h2><?=$v["lot"]->company?></h2>
                        <p>
                            Lot: <?=$v["lot"]->number?><br/>
                            Administered: <?=$v["datetime"]?><br/>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button onclick="document.cookie='view=table;';window.location.reload()">View Table</button>
            <?php endif ?>
        </section>
        <?php endif; ?>
    </div>
</body>

</html>