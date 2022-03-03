<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
include_once(BACKEND_DIR."patients.php");

$missing = [];
if(!checkGet(["ohip"], $missing)){
    apiResp(false, "Missing args: " . join(",", $missing));
}

$p = getPatient($_GET["ohip"]);
if($p == null)
    apiResp(false, "Patient not found");
apiResp(true, "", "patient", $p->toAssoc());