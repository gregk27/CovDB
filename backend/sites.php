<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/_include.php");
include_once(MODELS_DIR."Site.php");
include_once(MODELS_DIR."Nurse.php");

function getSites() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Site");
    $stmt->execute();
    $out = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $out[] = Site::fromAssoc($row);
    }
    return $out;
}

function getSiteDates(string $site) {
    global $conn;
    $stmt = $conn->prepare("SELECT date FROM SiteDate WHERE site=:site");
    $stmt->bindParam(":site", $site);
    $stmt->execute();
    $out = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if($out == null) $out = [];
    return $out;
}

function getSiteLotNumbers(string $site) {
    global $conn;
    $stmt = $conn->prepare("SELECT number FROM Lot WHERE site=:site");
    $stmt->bindParam(":site", $site);
    $stmt->execute();
    $out = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    if($out == null) $out = [];
    return $out;
}

function getWorkers(string $site) {
    global $conn;
    $stmt = $conn->prepare(
    "(SELECT ID, firstName, lastName, 'Nurse' AS type FROM Nurse
     JOIN NurseWorksAt nwa ON nwa.nurse = Nurse.ID
     WHERE nwa.site = ?)
     UNION
     (SELECT ID, firstName, lastName, 'Doctor' AS type FROM Doctor
     JOIN DoctorWorksAt dwa ON dwa.doctor = Doctor.ID
     WHERE dwa.site = ?)
     ORDER BY lastName
    ");
    $stmt->execute([$site, $site]);
    $out = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // Kinda bad practice, but using Nurse as all attributes are shared
        $out[] = ["type"=>$row["type"], "data"=>Nurse::fromAssoc($row)];
    }
    return $out;
}