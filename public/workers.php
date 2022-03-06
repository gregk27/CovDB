<!DOCTYPE html>
<html>
<?php 
    define("NAME", "Worker Lookup");
    include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
    include_once(BACKEND_DIR."/patients.php");
    include_once(BACKEND_DIR."/sites.php");
    include_once(COMMON_ELEMENTS);
?>
<head>
    <?php insertMeta(); ?>
    <script src='main.js'></script>
    <style>
        #content {
            display:grid;
            grid-template-columns: 30% auto;
            height: 70vh;
        }
        #sites {
            overflow-x:hidden;
            overflow-y:scroll;
            height:inherit;
        }
        #info {
            margin: 0 2em 0 3em;
        }
    </style>
</head>
<body>
    <?php insertHeader(); ?>
    <div id="content">
        <section id="sites">
        <?php foreach(getSites() as $s):
            $selected = "";
            if(isset($_GET['s']) && $_GET['s'] == $s->name){
                $selected = "selected";
            }
            ?>
            <a class="panel clickable <?= $selected ?>" href="?s=<?=$s->name?>" style="width:70%; margin-left:auto; margin-right:auto">
                <i class="fa-solid fa-user"></i>
                <h2><?= $s->name ?></h2>
                <p><?= $s->street." ".$s->city." ".$s->province ?></p>
            </a>
        <?php endforeach; ?>
        </section>
        <?php if(isset($_GET['s'])): 
             $workers = getWorkers($_GET['s']);
             ?>
        <section id="info">
            <div class="panelGrid">
                <?php foreach($workers as $w):?>
                <div class="panel">
                    <h2><?=$w["data"]->firstName." ".$w["data"]->lastName?></h2>
                    <p>
                        <?=$w["type"]?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</body>

</html>