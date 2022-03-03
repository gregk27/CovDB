<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
include_once(BACKEND_DIR."sites.php");

$missing = [];
if(!checkGet(["site"], $missing)){
    apiResp(false, "Missing args: " . join(",", $missing));
}


$dates = getSiteDates($_GET["site"]);
$lots = getSiteLotNumbers($_GET["site"]);
apiResp(true, "", "data", array("dates"=>$dates, "lots"=>$lots));