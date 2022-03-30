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
    <link rel='stylesheet' type='text/css' href='/listpage.css'>
</head>
<body>
    <?php insertHeader(); ?>
    <div id="content">
        <section id="sidebar">
        <?php foreach(getSites() as $s):
            $selected = "";
            if(isset($_GET['s']) && $_GET['s'] == $s->name){
                $selected = "selected";
            }
            ?>
            <a class="panel clickable <?= $selected ?>" href="?s=<?=$s->name . (isset($_GET['view']) ? "&view=".$_GET['view'] : "")?>" style="width:70%; margin-left:auto; margin-right:auto">
                <i class="fa-solid fa-location-dot"></i>
                <h2><?= $s->name ?></h2>
                <p><?= $s->street." ".$s->city." ".$s->province ?></p>
            </a>
        <?php endforeach; ?>
        </section>
        <?php if(isset($_GET['s'])): 
             $workers = getWorkers($_GET['s']);
             ?>
        <section id="info">
            <h2>Employees at <?=$_GET['s']?>:</h2>
            <?php if(isset($_GET['view']) && $_GET['view']=='table'): ?>
                <div>
                   <table>
                    <tr><th>Firstname</th><th>Lastname</th><th>Job</th></tr>
                    <?php foreach($workers as $w):?>
                    <tr>
                        <td><?=$w["data"]->firstName?></td>
                        <td><?=$w["data"]->lastName?></td>
                        <td><?=$w["type"]?></td>
                    </tr>
                    <?php endforeach; ?> 
                    </table>
                </div>
                <a href="?s=<?=$_GET['s']?>" class="button">View Panels</a>
            <?php else: ?>    
                <div class="panelGrid">
                    <?php foreach($workers as $w):?>
                    <div class="panel">
                        <i class="fa-solid fa-user-<?=strtolower($w["type"])?>"></i>
                        <h2><?=$w["data"]->firstName." ".$w["data"]->lastName?></h2>
                        <p>
                            <?=$w["type"]?>
                        </p>
                    </div>
                    <?php endforeach;
                    if(count($workers) == 0)
                        echo "<h2 style='text-align:center'>No workers at " . $_GET['s'] . "</h2>"
                    ?>
                </div>
                <a href="?s=<?=$_GET['s']?>&view=table" class="button">View Table</a>
            <?php endif; ?>
        </section>
        <?php endif; ?>
    </div>
</body>

</html>