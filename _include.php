<?php
// Global include header, contains setup and utils

// Setup database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=covidDB;user=root');
} catch (Exception $e) {
    die("Database connection failed with message: ".$e);
}

// Various shorthand defines to project folders
define("BACKEND_DIR", $_SERVER["DOCUMENT_ROOT"] . "/backend/");
define("MODELS_DIR", $_SERVER["DOCUMENT_ROOT"] . "/models/");
define("API_DIR", $_SERVER["DOCUMENT_ROOT"] . "/api/");
define("IMAGES_DIR", $_SERVER["DOCUMENT_ROOT"] . "/public/images/");

// API Utilities migrated from past project: https://github.com/gregk27/TasQ-Backend/blob/master/backend/utils.php

/**
 * Check that passed values exist in the GET statement
 * @param $values array Array of values to check for
 * @param $missing array Optional array to be populated with missing value names
 * @return bool false if a value is missing
 */
function checkGet($values, &$missing=[]){
    foreach($values as $val) {
        if (!isset($_GET[$val]))
            array_push($missing, $val);
    }
    return count($missing) == 0;
}

/**
 * Check that passed values exist in the POST statement
 * @param $values array Array of values to check for
 * @param $missing array Optional array to be populated with missing value names
 * @return bool false if a value is missing
 */
function checkPost($values, &$missing=[]){
    foreach($values as $val) {
        if (!isset($_POST[$val]))
            array_push($missing, $val);
    }
    return count($missing) == 0;
}

/**
 * Output JSON api response
 * @param $success bool Success flag
 * @param $error string Error message, default ""
 * @param $payloadName string Name of payload to return, unused if payload is null
 * @param $payload array|null Associative array payload, will be converted to JSON
 * @param $exit bool If true, will exit after outputting response
 */
function apiResp(bool $success, string $error="", string $payloadName="", array $payload=null, bool $exit=true){
    header('Content-type: application/json');
    $json = '{"success":' . ($success ? 'true' : 'false');
    $json .= ', "error":"' . str_replace("\"", "'", $error) . '"';
    if($payload != null){
        $json .= ", \"$payloadName\":" . json_encode($payload, JSON_NUMERIC_CHECK);
    }
    echo $json . "}";
    if($exit) exit($success ? 0 : 1);
}

