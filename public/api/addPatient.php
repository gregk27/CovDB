<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/_include.php");
include_once(MODELS_DIR."Patient.php");
include_once(BACKEND_DIR."patients.php");

$missing = [];
if(!checkPost(["OHIP", "firstName", "lastName", "dateOfBirth"], $missing)){
    apiResp(false, "Missing args: " . join(",", $missing));
}

addPatient(Patient::fromAssoc($_POST));

apiResp(true);