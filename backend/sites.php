<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/_include.php");
include_once(MODELS_DIR."Site.php");

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