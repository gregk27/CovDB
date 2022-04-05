<?php
// Fuctions related to vaccine records

include_once($_SERVER["DOCUMENT_ROOT"] . "/_include.php");
include_once(MODELS_DIR."Vaccination.php");

/**
 * Add a new Vaccination to the database.
 * @param Vaccination $v vaccination data to be added
 */
function addVaccination(Vaccination $v){
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Vaccination (patient, site, lot, datetime) VALUES (?, ?, ?, ?)");
    $stmt->execute([$v->patient, $v->site, $v->lot, $v->datetime]);
}