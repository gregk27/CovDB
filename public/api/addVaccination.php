<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
include_once(MODELS_DIR."Vaccination.php");
include_once(BACKEND_DIR."vaccinations.php");

$missing = [];
if(!checkPost(["patient", "site", "lot", "datetime"], $missing)){
    apiResp(false, "Missing args: " . join(",", $missing));
}

addVaccination(Vaccination::fromAssoc($_POST));

apiResp(true);