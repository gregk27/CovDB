<?php
// Functions relating to offered vaccines

include_once($_SERVER["DOCUMENT_ROOT"] . "/_include.php");
include_once(MODELS_DIR."Company.php");
include_once(MODELS_DIR."Site.php");

/**
 * Get list of companies offering vaccines.
 * @return Company[] list of companies in database
 */
function getCompanies() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Company");
    $stmt->execute();
    $out = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $out[] = Company::fromAssoc($row);
    }
    return $out;
}

/**
 * Get sites which have a specified vaccine
 * @param string $company Name of company producing vaccine
 * @return array with structure
 * [
 *   site: Site,
 *   doses: int
 * ]
 */
function getSitesWithVax(string $company) {
    global $conn;
    $stmt = $conn->prepare("SELECT Site.*, SUM(Lot.doses) AS doses FROM Company JOIN Lot on Lot.company = Company.name JOIN Site on Lot.site = Site.name WHERE Company=? GROUP BY name");
    $stmt->execute([$company]);
    $out = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $out[] = ['site'=>Site::fromAssoc($row), 'doses'=>$row["doses"]];
    }
    return $out;
}