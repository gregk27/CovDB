<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/_include.php");
include_once(MODELS_DIR."Vaccination.php");


function addVaccination(Vaccination $v){
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Vaccination (patient, site, lot, datetime) VALUES (?, ?, ?, ?)");
    $stmt->execute([$v->patient, $v->site, $v->lot, $v->datetime]);
}

function getVaccinationsForPatient(string $ohip) {
    
}