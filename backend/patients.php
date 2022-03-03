<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/_include.php");
include_once(MODELS_DIR."Patient.php");

function getPatient(string $ohip): Patient | null {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Patient WHERE OHIP=:ohip");
    $stmt->bindParam(":ohip", $ohip);
    $stmt->execute();
    $res = $stmt->fetchAll();
    if(count($res) == 0){
        return null;
    }
    return Patient::fromAssoc($res[0]);
}

function addPatient(Patient $p){
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Patient (OHIP, firstName, lastName, dateOfBirth) VALUES (?, ?, ?, ?)");
    $stmt->execute([$p->OHIP, $p->firstName, $p->lastName, $p->dateOfBirth]);
}